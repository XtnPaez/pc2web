<?php
/**
 * exporter.php — pc2web v1.2
 * ---------------------------------------------------------------
 * Empaqueta un proyecto autosuficiente en /build/export/<proyecto>/
 * a partir de la cache activa devuelta por validation.php.
 *
 * ENTRADA (POST):
 *  - project_name  : string  Nombre del proyecto.
 *  - cache_path    : string  Ruta relativa a la carpeta de cache activa.
 *
 * SALIDA (JSON):
 *  - { status: "ok", export_dir: ".../build/export/<proyecto>/" }
 *  - { status: "error", errors: [ ... ] }
 */

declare(strict_types=1);

// ---------------------------------------------------------------
// Utilidades
// ---------------------------------------------------------------

function respond(array $payload, int $code = 200): void {
  http_response_code($code);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  exit;
}

function slugify(string $name): string {
  $name = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name);
  $name = strtolower($name);
  $name = preg_replace('/[^a-z0-9\-_\s]+/', '', $name);
  $name = preg_replace('/[\s_]+/', '-', $name);
  return substr(trim($name, '-'), 0, 64) ?: 'proyecto';
}

function rcopy(string $src, string $dst): bool {
  if (!is_dir($src)) return copy($src, $dst);
  if (!is_dir($dst)) mkdir($dst, 0775, true);
  foreach (scandir($src) as $f) {
    if ($f === '.' || $f === '..') continue;
    $srcPath = "$src/$f";
    $dstPath = "$dst/$f";
    if (is_dir($srcPath)) rcopy($srcPath, $dstPath);
    else copy($srcPath, $dstPath);
  }
  return true;
}

function rrmdir(string $dir): bool {
  if (!file_exists($dir)) return true;
  if (!is_dir($dir)) return unlink($dir);
  foreach (scandir($dir) as $f) {
    if ($f === '.' || $f === '..') continue;
    $p = "$dir/$f";
    is_dir($p) ? rrmdir($p) : unlink($p);
  }
  return rmdir($dir);
}

function render_index_html(): string {
  ob_start();
  define('EXPORT_MODE', true);
  require __DIR__ . '/index.php';
  return ob_get_clean();
}

function strip_producer_ui(string $html): string {
  // 1. Quitar comentarios que identifican modo productor
  $html = preg_replace('#<!--\s*={3,}[^>]*modo productor[^>]*={3,}\s*-->#si', '', $html);

  // 2. Eliminar botones o bloques con IDs conocidos
  $ids = [
    'btnUploadLayer', 'btnValidateLayer', 'btnPopupConfig', 'btnLoadAnother',
    'btnExportProject', 'producerToolbar',
    'btnValidate', 'btnExport' // nuevos del navbar actual
  ];
  foreach ($ids as $id) {
    $html = preg_replace('#<[^>]*id=["\']'.$id.'["\'][^>]*>.*?</[^>]+>#si', '', $html);
    $html = preg_replace('#<[^>]*id=["\']'.$id.'["\'][^>]*/?>#si', '', $html);
  }

  // 3. Eliminar <li> vacíos en la navbar
  $html = preg_replace('#<li[^>]*>\s*</li>#si', '', $html);

  // 4. Si queda un bloque <ul> vacío, eliminarlo también
  $html = preg_replace('#<ul[^>]*>\s*</ul>#si', '', $html);

  return $html;
}

function replace_navbar_brand(string $html, string $name): string {
  return preg_replace_callback(
    '#(<[^>]*class=["\'][^"\']*navbar-brand[^"\']*["\'][^>]*>)(.*?)(</[^>]+>)#si',
    fn($m)=>$m[1].htmlspecialchars($name,ENT_QUOTES,'UTF-8').$m[3],
    $html,1
  );
}

function rewrite_asset_paths(string $html): string {
  $html = preg_replace('#(<link[^>]+href=["\'])/?css/custom\.css(["\'])#i','$1./assets/custom.css$2',$html);
  $html = preg_replace('#(<script[^>]+src=["\'])/?js/map\.js(["\'])#i','$1./assets/map.js$2',$html);
  $html = preg_replace('#(<script[^>]+src=["\'])/?js/producer\.js(["\'])#i','$1./assets/producer.js$2',$html);
  return $html;
}

function write_file_ensured(string $path, string $content): bool {
  $dir = dirname($path);
  if (!is_dir($dir)) mkdir($dir, 0775, true);
  return (bool)file_put_contents($path, $content);
}

// ---------------------------------------------------------------
// Validación de entrada
// ---------------------------------------------------------------

$name = $_POST['project_name'] ?? '';
$cache = $_POST['cache_path'] ?? '';
if (!$name || !$cache) respond(['status'=>'error','errors'=>['Faltan parámetros']],400);
$slug = slugify($name);

if (!preg_match('#^data/cache/[^/]+#',$cache)) respond(['status'=>'error','errors'=>['Ruta cache inválida']],400);
$cacheAbs = __DIR__.'/'.$cache;
if (!is_dir($cacheAbs)) respond(['status'=>'error','errors'=>["No existe $cache"]],400);

// ---------------------------------------------------------------
// Carpetas destino
// ---------------------------------------------------------------

$exportDir = __DIR__."/build/export/$slug";
$assetsDir = "$exportDir/assets";
$exportCache = "$exportDir/data/cache/".basename($cacheAbs);

if (is_dir($exportDir)) rrmdir($exportDir);
mkdir($assetsDir,0775,true);

// ---------------------------------------------------------------
// Copia assets
// ---------------------------------------------------------------

$assets = [
  __DIR__.'/js/map.js'      => "$assetsDir/map.js",
  __DIR__.'/js/producer.js' => "$assetsDir/producer.js",
  __DIR__.'/css/custom.css' => "$assetsDir/custom.css"
];
foreach ($assets as $src=>$dst)
  if (!copy($src,$dst))
    respond(['status'=>'error','errors'=>["Fallo copiando ".basename($src)]],500);

// ---------------------------------------------------------------
// Copia cache
// ---------------------------------------------------------------

rcopy($cacheAbs,$exportCache);

// ---------------------------------------------------------------
// Genera index.html
// ---------------------------------------------------------------

$html = render_index_html();
$html = strip_producer_ui($html);
$html = replace_navbar_brand($html,$name);
$html = rewrite_asset_paths($html);
if (!preg_match('#<meta[^>]+charset#i',$html))
  $html = preg_replace('#(<head[^>]*>)#i','$1<meta charset="utf-8">',$html,1);
write_file_ensured("$exportDir/index.html",$html);

// ---------------------------------------------------------------
// Limpieza de entradas originales
// ---------------------------------------------------------------

foreach (['data/input','data/cache'] as $p){
  $abs = __DIR__.'/'.$p;
  if (!is_dir($abs)) continue;
  foreach (array_diff(scandir($abs),['.','..']) as $f){
    $fp="$abs/$f";
    is_dir($fp)?rrmdir($fp):@unlink($fp);
  }
}

// ---------------------------------------------------------------
// Salida
// ---------------------------------------------------------------

respond(['status'=>'ok','export_dir'=>"build/export/$slug/"]);
?>

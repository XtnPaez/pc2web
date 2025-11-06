<?php
// src/core/save_popup_config.php
// Guarda popup_config_<layerKey>.json dentro de la cache tmp activa.
// Entrada JSON: { cache_dir: "/data/cache/tmp_YYYYMMDD_HHMMSS", layer_key: "viviendas", config: {...} }
// Salida JSON: { ok: true, path: "..." }

header('Content-Type: application/json; charset=utf-8');

// Solo POST con JSON
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
  exit;
}

$raw = file_get_contents('php://input');
if (!$raw) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Body vacío']);
  exit;
}

$data = json_decode($raw, true);
if (!$data || !isset($data['cache_dir'], $data['layer_key'], $data['config'])) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'JSON inválido']);
  exit;
}

$cacheDir = $data['cache_dir'];
$layerKey = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $data['layer_key']); // sanitizar nombre
$config   = $data['config'];

// Validaciones mínimas
if (!is_dir($_SERVER['DOCUMENT_ROOT'] . parse_url($cacheDir, PHP_URL_PATH))) {
  // Intentar ruta relativa al script si DOCUMENT_ROOT no aplica
  $abs = realpath(__DIR__ . '/../../' . ltrim($cacheDir, '/'));
  if ($abs === false || !is_dir($abs)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'cache_dir no existe']);
    exit;
  }
  $cacheAbs = $abs;
} else {
  $cacheAbs = realpath($_SERVER['DOCUMENT_ROOT'] . parse_url($cacheDir, PHP_URL_PATH));
}

if ($cacheAbs === false) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'No se pudo resolver cache_dir']);
  exit;
}

// Nombre de archivo destino
$dest = $cacheAbs . DIRECTORY_SEPARATOR . 'popup_config_' . $layerKey . '.json';

// Guardar JSON con pretty print
$bytes = file_put_contents($dest, json_encode($config, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
if ($bytes === false) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => 'No se pudo escribir el archivo']);
  exit;
}

echo json_encode(['ok' => true, 'path' => str_replace(realpath(__DIR__ . '/../../'), '', $dest)]);

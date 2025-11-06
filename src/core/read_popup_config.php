<?php
// src/core/read_popup_config.php
// Devuelve popup_config_<layerKey>.json si existe.
// GET params: cache_dir=/data/cache/tmp_YYYY... & layer_key=viviendas

header('Content-Type: application/json; charset=utf-8');

$cacheDir = isset($_GET['cache_dir']) ? $_GET['cache_dir'] : null;
$layerKey = isset($_GET['layer_key']) ? $_GET['layer_key'] : null;

if (!$cacheDir || !$layerKey) {
  http_response_code(400);
  echo json_encode(['error' => 'Parámetros requeridos: cache_dir, layer_key']);
  exit;
}

$layerKey = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $layerKey);

// Resolver ruta absoluta
$cacheAbs = null;
if (is_dir($_SERVER['DOCUMENT_ROOT'] . parse_url($cacheDir, PHP_URL_PATH))) {
  $cacheAbs = realpath($_SERVER['DOCUMENT_ROOT'] . parse_url($cacheDir, PHP_URL_PATH));
} else {
  $abs = realpath(__DIR__ . '/../../' . ltrim($cacheDir, '/'));
  if ($abs !== false && is_dir($abs)) {
    $cacheAbs = $abs;
  }
}
if ($cacheAbs === false || $cacheAbs === null) {
  http_response_code(400);
  echo json_encode(['error' => 'cache_dir no existe']);
  exit;
}

$file = $cacheAbs . DIRECTORY_SEPARATOR . 'popup_config_' . $layerKey . '.json';
if (!is_file($file)) {
  http_response_code(404);
  echo json_encode(['error' => 'No existe configuración para esta capa']);
  exit;
}

// Leer y devolver
$raw = file_get_contents($file);
if ($raw === false) {
  http_response_code(500);
  echo json_encode(['error' => 'No se pudo leer el archivo']);
  exit;
}

echo $raw;

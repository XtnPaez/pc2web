<?php
// upload.php
// Guarda archivos GeoJSON + SLD + metadatos JSON en /data/input/uploads/

header('Content-Type: application/json');

$uploadDir = __DIR__ . '/data/input/uploads/';
$maxSize = 100 * 1024 * 1024; // 100 MB

if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);

$geo = $_FILES['geojsonFile'] ?? null;
$sld = $_FILES['sldFile'] ?? null;
$name = trim($_POST['layerName'] ?? '');
$desc = trim($_POST['layerDesc'] ?? '');

if (!$geo || !$sld || !$name) {
  echo json_encode(["status" => "error", "message" => "Datos incompletos."]);
  exit;
}

if ($geo['error'] !== UPLOAD_ERR_OK || $sld['error'] !== UPLOAD_ERR_OK) {
  echo json_encode(["status" => "error", "message" => "Error al subir archivos."]);
  exit;
}

// Validar extensiones
$extGeo = strtolower(pathinfo($geo['name'], PATHINFO_EXTENSION));
$extSld = strtolower(pathinfo($sld['name'], PATHINFO_EXTENSION));
if ($extGeo !== 'geojson' || $extSld !== 'sld') {
  echo json_encode(["status" => "error", "message" => "Solo se aceptan archivos .geojson y .sld"]);
  exit;
}

// Validar tamaño
if ($geo['size'] > $maxSize || $sld['size'] > $maxSize) {
  echo json_encode(["status" => "error", "message" => "Los archivos no deben superar los 100 MB"]);
  exit;
}

// Validar nombres base
$baseGeo = pathinfo($geo['name'], PATHINFO_FILENAME);
$baseSld = pathinfo($sld['name'], PATHINFO_FILENAME);
if ($baseGeo !== $baseSld) {
  echo json_encode(["status" => "error", "message" => "Los nombres base deben coincidir."]);
  exit;
}

// Guardar archivos
move_uploaded_file($geo['tmp_name'], $uploadDir . basename($geo['name']));
move_uploaded_file($sld['tmp_name'], $uploadDir . basename($sld['name']));

// Crear archivo de metadatos
$meta = [
  'nombre' => $name,
  'descripcion' => $desc
];
file_put_contents($uploadDir . $baseGeo . '_metadatos.json', json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(["status" => "success", "message" => "Capa subida correctamente."]);

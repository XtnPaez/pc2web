<?php
// ===============================================================
// list_layers.php
// Devuelve en formato JSON todas las capas validadas disponibles
// en /data/cache/ (sin exigir que coincidan nombre y carpeta).
// ===============================================================

header('Content-Type: application/json');

$cacheDir = __DIR__ . '/../../data/cache/';
$layers = [];

if (!is_dir($cacheDir)) {
    echo json_encode(['error' => 'No existe la carpeta /data/cache/']);
    exit;
}

// Recorre todas las subcarpetas dentro de /data/cache/
foreach (glob($cacheDir . '*', GLOB_ONLYDIR) as $layerDir) {
    $geojsonFiles = glob("$layerDir/*.geojson");
    $sldFiles = glob("$layerDir/*.sld");
    $metaFiles = glob("$layerDir/*.json");

    // Requiere al menos un .geojson y un .sld
    if (count($geojsonFiles) === 0 || count($sldFiles) === 0) {
        continue;
    }

    // Usa el primer archivo .geojson encontrado
    $geojsonPath = $geojsonFiles[0];
    $sldPath = $sldFiles[0];
    $metaPath = $metaFiles[0] ?? null;

    $layerName = basename($geojsonPath, '.geojson');
    $relativeDir = str_replace($cacheDir, 'data/cache/', $layerDir);

    $layer = [
        'name' => $layerName,
        'geojson' => "$relativeDir/" . basename($geojsonPath),
        'sld' => "$relativeDir/" . basename($sldPath),
        'metadata' => $metaPath ? json_decode(file_get_contents($metaPath), true) : null
    ];

    $layers[] = $layer;
}

// Devuelve el listado JSON
echo json_encode($layers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>

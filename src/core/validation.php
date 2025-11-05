<?php
// ============================================================
// src/core/validation.php
// ------------------------------------------------------------
// Valida los insumos subidos en /data/input/uploads/.
// - Verifica pares (.geojson + .sld + .meta.json) con mismo basename.
// - Crea carpeta cache tmp_YYYYMMDD_HHMMSS/.
// - Copia los archivos válidos y genera layers.json.
// - Devuelve JSON con summary de resultados.
// ============================================================

error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

try {
    // ----- Rutas base -----
    $root     = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'pc2web';
    $inputDir = $root . '/data/input/uploads/';
    $cacheDir = $root . '/data/cache/';

    if (!is_dir($inputDir)) {
        echo json_encode(['success' => false, 'error' => "No existe carpeta de entrada: $inputDir"]);
        exit;
    }
    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0775, true);
    }

    // ----- Escanear uploads -----
    $files = scandir($inputDir);
    $bases = [];
    foreach ($files as $f) {
        if ($f === '.' || $f === '..') continue;
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        $base = pathinfo($f, PATHINFO_FILENAME);

        // Detectar .meta.json correctamente
        if (str_ends_with($f, '.meta.json')) {
            $base = substr($f, 0, -10); // elimina ".meta.json"
            $ext = 'meta';
        }

        if (!isset($bases[$base])) {
            $bases[$base] = ['geojson' => null, 'sld' => null, 'meta' => null];
        }

        if ($ext === 'geojson') $bases[$base]['geojson'] = $f;
        if ($ext === 'sld')     $bases[$base]['sld']     = $f;
        if ($ext === 'meta')    $bases[$base]['meta']    = $f;
    }

    // ----- Crear carpeta temporal -----
    $timestamp = date('Ymd_His');
    $tmpName   = "tmp_$timestamp";
    $tmpPath   = $cacheDir . $tmpName . '/';
    @mkdir($tmpPath, 0775, true);

    if (!is_dir($tmpPath)) {
        echo json_encode(['success' => false, 'error' => "No se pudo crear carpeta temporal: $tmpPath"]);
        exit;
    }

    // ----- Procesar capas -----
    $validated = [];
    $missing   = [];
    $skipped   = [];
    $layersOut = [];

    foreach ($bases as $base => $fileset) {
        $geo = $fileset['geojson'];
        $sld = $fileset['sld'];
        $meta = $fileset['meta'];

        if (!$geo || !$sld) {
            $missing[] = $base;
            continue;
        }

        // Verificar existencia física
        $geoPath = $inputDir . $geo;
        $sldPath = $inputDir . $sld;
        $metaPath = $meta ? $inputDir . $meta : null;

        if (!file_exists($geoPath) || !file_exists($sldPath)) {
            $missing[] = $base;
            continue;
        }

        // Leer metadatos si existen
        $metaData = null;
        if ($metaPath && file_exists($metaPath)) {
            $json = file_get_contents($metaPath);
            $metaData = json_decode($json, true);
        }

        // Copiar archivos a tmp
        $ok1 = copy($geoPath, $tmpPath . $geo);
        $ok2 = copy($sldPath, $tmpPath . $sld);
        if ($metaPath && file_exists($metaPath)) copy($metaPath, $tmpPath . basename($metaPath));

        if ($ok1 && $ok2) {
            $validated[] = $base;

            $layersOut[] = [
                'basename' => $base,
                'nombre_capa' => $metaData['nombre_capa'] ?? $base,
                'descripcion_capa' => $metaData['descripcion_capa'] ?? '',
                'archivo_geojson' => $geo,
                'archivo_sld' => $sld,
                'meta_file' => $meta ?? null,
                'validated_at' => date('c')
            ];
        } else {
            $skipped[] = $base;
        }
    }

    // ----- Generar layers.json -----
    $layersJsonPath = $tmpPath . 'layers.json';
    file_put_contents($layersJsonPath, json_encode($layersOut, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    // ----- Respuesta -----
    $out = [
        'success'   => true,
        'cache_dir' => $tmpName,
        'validated' => $validated,
        'missing'   => $missing,
        'skipped'   => $skipped,
        'total'     => count($bases)
    ];

    echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Excepción: ' . $e->getMessage()
    ]);
}

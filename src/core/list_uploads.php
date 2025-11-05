<?php
// ============================================================
// src/core/list_uploads.php
// ------------------------------------------------------------
// Lista los basenames presentes en /data/input/uploads/
// Retorna: { uploads: [{ basename, geojson, sld, meta }] }
// Se usa para sincronizar UI antes de validar.
// ============================================================

error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

try {
    $root = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'pc2web';
    $dir  = $root . '/data/input/uploads/';

    $out = [];
    if (is_dir($dir)) {
        // Agrupamos por basename
        $files = scandir($dir);
        $byBase = [];
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            $path = $dir . $f;
            if (!is_file($path)) continue;

            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
            $base = pathinfo($f, PATHINFO_FILENAME);
            if (!isset($byBase[$base])) $byBase[$base] = ['basename' => $base, 'geojson' => null, 'sld' => null, 'meta' => null];

            if ($ext === 'geojson') $byBase[$base]['geojson'] = $f;
            if ($ext === 'sld')     $byBase[$base]['sld']     = $f;
            if ($ext === 'json' && str_ends_with($f, '.meta.json')) $byBase[$base]['meta'] = $f;
        }
        // Solo devolver basenames con al menos geojson o sld
        foreach ($byBase as $b) {
            if ($b['geojson'] || $b['sld']) $out[] = $b;
        }
    }

    echo json_encode(['uploads' => $out], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    echo json_encode(['uploads' => [], 'error' => $e->getMessage()]);
}

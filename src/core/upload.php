<?php
// ============================================================
// src/core/upload.php
// ------------------------------------------------------------
// Recibe .geojson + .sld + nombre/descripcion.
// Guarda en /data/input/uploads/ y crea <basename>.meta.json
// con nombre/descripcion (para "Sobre el mapa" en exportación).
// Límite de tamaño: 100 MB por archivo.
// ============================================================

error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

try {
    // Directorio base del proyecto
    $root = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'pc2web';
    $uploadDir = $root . '/data/input/uploads/';

    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0775, true);
        if (!is_dir($uploadDir)) {
            echo json_encode(['success' => false, 'error' => 'No se pudo crear la carpeta de uploads.']);
            exit;
        }
    }

    // Validaciones básicas de inputs
    if (!isset($_FILES['geojson']) || !isset($_FILES['sld'])) {
        echo json_encode(['success' => false, 'error' => 'Archivos incompletos.']);
        exit;
    }
    $nombre = isset($_POST['nombre_capa']) ? trim($_POST['nombre_capa']) : '';
    $descripcion = isset($_POST['descripcion_capa']) ? trim($_POST['descripcion_capa']) : '';
    if ($nombre === '' || $descripcion === '') {
        echo json_encode(['success' => false, 'error' => 'Nombre y descripción son obligatorios.']);
        exit;
    }

    // Límite 100 MB por archivo
    $maxBytes = 100 * 1024 * 1024;

    $geo = $_FILES['geojson'];
    $sld = $_FILES['sld'];

    if ($geo['error'] !== UPLOAD_ERR_OK || $sld['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'error' => 'Error al recibir archivos.']);
        exit;
    }
    if ($geo['size'] > $maxBytes || $sld['size'] > $maxBytes) {
        echo json_encode(['success' => false, 'error' => 'Algún archivo supera 100 MB.']);
        exit;
    }

    // Extensiones
    $geoName = $geo['name'];
    $sldName = $sld['name'];
    $geoExt = strtolower(pathinfo($geoName, PATHINFO_EXTENSION));
    $sldExt = strtolower(pathinfo($sldName, PATHINFO_EXTENSION));

    if ($geoExt !== 'geojson') {
        echo json_encode(['success' => false, 'error' => 'El archivo GeoJSON debe tener extensión .geojson.']);
        exit;
    }
    if ($sldExt !== 'sld') {
        echo json_encode(['success' => false, 'error' => 'El archivo SLD debe tener extensión .sld.']);
        exit;
    }

    // Coincidencia de nombre base
    $baseGeo = pathinfo($geoName, PATHINFO_FILENAME);
    $baseSld = pathinfo($sldName, PATHINFO_FILENAME);
    if ($baseGeo !== $baseSld) {
        echo json_encode(['success' => false, 'error' => 'Los nombres base no coinciden entre GeoJSON y SLD.']);
        exit;
    }

    // Mover archivos
    $geoPath = $uploadDir . $geoName;
    $sldPath = $uploadDir . $sldName;

    if (!move_uploaded_file($geo['tmp_name'], $geoPath)) {
        echo json_encode(['success' => false, 'error' => 'No se pudo guardar el GeoJSON.']);
        exit;
    }
    if (!move_uploaded_file($sld['tmp_name'], $sldPath)) {
        // rollback geo si falla sld
        @unlink($geoPath);
        echo json_encode(['success' => false, 'error' => 'No se pudo guardar el SLD.']);
        exit;
    }

    // Crear META JSON auxiliar en uploads (lo consumirá el validador/exportador)
    $meta = [
        'nombre_capa'      => $nombre,
        'descripcion_capa' => $descripcion,
        'archivo_geojson'  => $geoName,
        'archivo_sld'      => $sldName,
        'basename'         => $baseGeo,
        'uploaded_at'      => date('c')
    ];
    $metaPath = $uploadDir . $baseGeo . '.meta.json';
    file_put_contents($metaPath, json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    echo json_encode(['success' => true, 'basename' => $baseGeo]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => 'Excepción: ' . $e->getMessage()]);
}

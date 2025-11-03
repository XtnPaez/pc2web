<?php
/**
 * ------------------------------------------------------------
 * pc2webmap - Validador de insumos del productor
 * ------------------------------------------------------------
 * Archivo: src/core/validate.php
 * Descripción:
 *   - Valida los archivos de entrada (GeoJSON y SLD) antes de generar un mapa.
 *   - Si ambos son válidos, los copia a /data/cache/tmp_<timestamp>/
 *     y genera un archivo layers.json para que el mapa pueda listarlos.
 *   - Registra un log de validación.
 *
 * Autor: Equipo pc2webmap
 * Fecha: 2025-11-03
 * ------------------------------------------------------------
 */

 // === CONFIGURACIÓN GENERAL ===
date_default_timezone_set('America/Argentina/Buenos_Aires');
ini_set('memory_limit', '512M');

// === Directorios base ===
$inputDir = __DIR__ . '/../../data/input/';
$cacheDir = __DIR__ . '/../../data/cache/';
$logFile  = __DIR__ . '/../../logs/validation.log';

// === Archivos esperados ===
// (en el futuro podrían parametrizarse por nombre dinámico)
$geojsonFile = $inputDir . 'provincias.geojson';
$sldFile     = $inputDir . 'provincias.sld';


// ------------------------------------------------------------
// FUNCIÓN: validar GeoJSON
// ------------------------------------------------------------
/**
 * Verifica existencia, formato y estructura básica del GeoJSON.
 *
 * @param string $path Ruta completa del archivo GeoJSON
 * @return true|string True si es válido, o mensaje de error
 */
function validate_geojson($path) {
    if (!file_exists($path)) return "No se encontró el archivo GeoJSON.";

    $content = file_get_contents($path);
    $json = json_decode($content, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return "El archivo GeoJSON tiene errores de formato JSON.";
    }

    if (!isset($json['features'])) {
        return "El archivo GeoJSON no contiene la clave 'features'.";
    }

    foreach ($json['features'] as $i => $f) {
        if (!isset($f['geometry']) || !isset($f['properties'])) {
            return "Feature #$i carece de geometry o properties.";
        }
    }

    return true;
}


// ------------------------------------------------------------
// FUNCIÓN: validar SLD
// ------------------------------------------------------------
/**
 * Verifica existencia y estructura básica de un archivo SLD.
 *
 * @param string $path Ruta completa del archivo SLD
 * @return true|string True si es válido, o mensaje de error
 */
function validate_sld($path) {
    if (!file_exists($path)) return "No se encontró el archivo SLD.";

    libxml_use_internal_errors(true);
    $xml = simplexml_load_file($path);
    if (!$xml) return "Error de sintaxis XML en el archivo SLD.";

    $namespaces = $xml->getNamespaces(true);
    $hasStyle = false;

    // CASO 1: estructura clásica sin prefijo
    if (isset($xml->NamedLayer->UserStyle->FeatureTypeStyle)) {
        $hasStyle = true;
    }

    // CASO 2: buscar con prefijo se: (QGIS moderno)
    if (!$hasStyle && isset($namespaces['se'])) {
        foreach ($xml->NamedLayer as $layer) {
            $seChildren = $layer->children($namespaces['se']);
            foreach ($seChildren as $tagName => $seNode) {
                if ($tagName === 'FeatureTypeStyle') {
                    $hasStyle = true;
                    break 2;
                }
            }
        }

        if (!$hasStyle) {
            foreach ($xml->xpath('//se:FeatureTypeStyle') as $fts) {
                $hasStyle = true;
                break;
            }
        }
    }

    // CASO 3: búsqueda genérica
    if (!$hasStyle) {
        $ftsNodes = $xml->xpath('//*[local-name()="FeatureTypeStyle"]');
        if ($ftsNodes && count($ftsNodes) > 0) {
            $hasStyle = true;
        }
    }

    return $hasStyle ? true : "El SLD no contiene estructuras válidas de estilo (FeatureTypeStyle o se:FeatureTypeStyle).";
}


// ------------------------------------------------------------
// PROCESO PRINCIPAL
// ------------------------------------------------------------

// Ejecutar validaciones
$geojsonOK = validate_geojson($geojsonFile);
$sldOK     = validate_sld($sldFile);

// Inicializar log
$logMsg = "[" . date("Y-m-d H:i:s") . "] ";

if ($geojsonOK === true && $sldOK === true) {

    // === Crear carpeta temporal ===
    $sessionDirName = 'tmp_' . date('Ymd_His');
    $sessionDir = $cacheDir . $sessionDirName;
    if (!is_dir($sessionDir)) {
        mkdir($sessionDir, 0777, true);
    }

    // === Copiar archivos validados ===
    $geojsonName = basename($geojsonFile);
    $sldName     = basename($sldFile);
    copy($geojsonFile, "$sessionDir/$geojsonName");
    copy($sldFile, "$sessionDir/$sldName");

    // === Generar descriptor layers.json ===
    $layerName = pathinfo($geojsonName, PATHINFO_FILENAME);
    $layerData = [
        [
            "name"    => $layerName,
            "geojson" => "data/cache/$sessionDirName/$geojsonName",
            "sld"     => "data/cache/$sessionDirName/$sldName"
        ]
    ];

    $layersJsonPath = "$sessionDir/layers.json";
    file_put_contents($layersJsonPath, json_encode($layerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    // === Registrar en log ===
    $logMsg .= "Validación exitosa. Archivos copiados a $sessionDir\n";

    // === Respuesta JSON para el frontend ===
    echo json_encode([
        "status" => "ok",
        "path" => "data/cache/$sessionDirName",
        "layer" => $layerData[0]
    ]);

} else {
    // Si hubo errores
    $errors = [];
    if ($geojsonOK !== true) $errors[] = $geojsonOK;
    if ($sldOK !== true) $errors[] = $sldOK;

    $logMsg .= "Errores de validación: " . implode("; ", $errors) . "\n";

    echo json_encode([
        "status" => "error",
        "errors" => $errors
    ]);
}

// === Guardar log ===
file_put_contents($logFile, $logMsg, FILE_APPEND);
?>

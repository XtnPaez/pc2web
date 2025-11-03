<?php
/**
 * ------------------------------------------------------------
 * pc2webmap - Validador de insumos del productor
 * ------------------------------------------------------------
 * Archivo: src/core/validate.php
 * Descripción:
 *   - Valida que los archivos de entrada (GeoJSON y SLD) tengan
 *     estructura válida antes de generar un mapa.
 *   - En caso exitoso, los copia a /data/cache/ con un timestamp.
 *   - Registra un log con el resultado de la validación.
 *
 * Dependencias:
 *   - PHP >= 7.4
 *   - Extensión libxml (para parseo XML)
 *
 * Autor: Equipo pc2webmap
 * Fecha: 2025-11-03
 * ------------------------------------------------------------
 */

 // === CONFIGURACIÓN GENERAL ===
date_default_timezone_set('America/Argentina/Buenos_Aires');

ini_set('memory_limit', '512M');

// Directorios base del proyecto (rutas relativas)
$inputDir = __DIR__ . '/../../data/input/';
$cacheDir = __DIR__ . '/../../data/cache/';
$logFile  = __DIR__ . '/../../logs/validation.log';

// Archivos esperados
$geojsonFile = $inputDir . 'provincias.geojson';
$sldFile     = $inputDir . 'provincias.sld';

// ------------------------------------------------------------
// FUNCIÓN: validar GeoJSON
// ------------------------------------------------------------
/**
 * Verifica la existencia, formato y estructura básica del GeoJSON.
 *
 * @param string $path Ruta del archivo GeoJSON
 * @return true|string Retorna true si es válido, o mensaje de error
 */
function validate_geojson($path) {
    if (!file_exists($path)) return "No se encontró el archivo GeoJSON.";

    // Leer y decodificar el JSON
    $content = file_get_contents($path);
    $json = json_decode($content, true);

    // Verificar formato
    if (json_last_error() !== JSON_ERROR_NONE) {
        return "El archivo GeoJSON tiene errores de formato JSON.";
    }

    // Estructura mínima: debe contener 'features'
    if (!isset($json['features'])) {
        return "El archivo GeoJSON no contiene la clave 'features'.";
    }

    // Validar cada feature
    foreach ($json['features'] as $i => $f) {
        if (!isset($f['geometry']) || !isset($f['properties'])) {
            return "Feature #$i carece de geometry o properties.";
        }
    }

    return true; // Todo correcto
}

// ------------------------------------------------------------
// FUNCIÓN: validar SLD
// ------------------------------------------------------------
/**
 * Verifica la existencia y estructura básica de un archivo SLD.
 *
 * @param string $path Ruta del archivo SLD
 * @return true|string Retorna true si es válido, o mensaje de error
 */
function validate_sld($path) {
    if (!file_exists($path)) return "No se encontró el archivo SLD.";

    libxml_use_internal_errors(true);
    $xml = simplexml_load_file($path);
    if (!$xml) return "Error de sintaxis XML en el archivo SLD.";

    // Obtener todos los espacios de nombres
    $namespaces = $xml->getNamespaces(true);
    $hasStyle = false;

    // 🔹 CASO 1: estructura clásica sin prefijo (SLD 1.0)
    if (isset($xml->NamedLayer->UserStyle->FeatureTypeStyle)) {
        $hasStyle = true;
    }

    // 🔹 CASO 2: buscar con prefijo se: (SLD 1.1, QGIS moderno)
    if (!$hasStyle && isset($namespaces['se'])) {
        // Recorremos NamedLayer dentro del documento principal
        foreach ($xml->NamedLayer as $layer) {
            // Obtenemos los hijos en el namespace se
            $seChildren = $layer->children($namespaces['se']);
            foreach ($seChildren as $tagName => $seNode) {
                if ($tagName === 'FeatureTypeStyle') {
                    $hasStyle = true;
                    break 2; // salimos de ambos bucles
                }
            }
        }

        // Algunos SLD ponen se:FeatureTypeStyle directamente bajo UserStyle
        if (!$hasStyle) {
            foreach ($xml->xpath('//se:FeatureTypeStyle') as $fts) {
                $hasStyle = true;
                break;
            }
        }
    }

    // 🔹 CASO 3: buscar etiquetas <FeatureTypeStyle> o <se:FeatureTypeStyle> en todo el documento
    if (!$hasStyle) {
        // Buscar todas las coincidencias posibles (sin importar el prefijo)
        $ftsNodes = $xml->xpath('//*[local-name()="FeatureTypeStyle"]');
        if ($ftsNodes && count($ftsNodes) > 0) {
            $hasStyle = true;
        }
    }

    // Evaluar resultado
    if (!$hasStyle) {
        return "El SLD no contiene estructuras válidas de estilo (FeatureTypeStyle o se:FeatureTypeStyle).";
    }

    return true;
}


// ------------------------------------------------------------
// PROCESO PRINCIPAL
// ------------------------------------------------------------

// Ejecutar validaciones
$geojsonOK = validate_geojson($geojsonFile);
$sldOK     = validate_sld($sldFile);

// Inicializar mensaje de log
$logMsg = "[" . date("Y-m-d H:i:s") . "] ";

// Si ambas validaciones son exitosas
if ($geojsonOK === true && $sldOK === true) {

    // Crear carpeta temporal en /data/cache/
    $sessionDir = $cacheDir . 'tmp_' . date('Ymd_His');
    if (!is_dir($sessionDir)) {
        mkdir($sessionDir, 0777, true);
    }

    // Copiar los archivos validados
    copy($geojsonFile, $sessionDir . '/provincias.geojson');
    copy($sldFile, $sessionDir . '/provincias.sld');

    $logMsg .= "Validación exitosa. Archivos copiados a $sessionDir\n";

    // Respuesta JSON para el front-end (si se llama vía fetch)
    echo json_encode([
        "status" => "ok",
        "path" => str_replace(__DIR__ . '/../../', '', $sessionDir)
    ]);

} else {
    // Construir lista de errores detectados
    $errors = [];
    if ($geojsonOK !== true) $errors[] = $geojsonOK;
    if ($sldOK !== true) $errors[] = $sldOK;

    $logMsg .= "Errores de validación: " . implode("; ", $errors) . "\n";

    echo json_encode([
        "status" => "error",
        "errors" => $errors
    ]);
}

// Guardar el resultado en logs/validation.log
file_put_contents($logFile, $logMsg, FILE_APPEND);

?>

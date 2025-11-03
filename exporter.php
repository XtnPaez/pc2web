<?php
/**
 * ============================================================
 * pc2webmap - exporter.php
 * ------------------------------------------------------------
 * Guarda la versión exportada (modo usuario) del proyecto.
 * Crea el archivo /build/export/index.html
 * ============================================================
 */

// Configuración básica
header('Content-Type: application/json; charset=utf-8');

// Ruta destino (relativa al proyecto)
$targetDir = __DIR__ . '/build/export/';
$targetFile = $targetDir . 'index.html';

// Leer contenido HTML enviado por fetch()
$htmlContent = file_get_contents('php://input');

// Verificar contenido
if (empty($htmlContent)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No se recibió contenido HTML para exportar.'
    ]);
    exit;
}

// Crear carpeta si no existe
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Intentar escribir el archivo
$result = file_put_contents($targetFile, $htmlContent);

// Responder al cliente
if ($result !== false) {
    echo json_encode([
        'status' => 'ok',
        'message' => 'Archivo exportado correctamente.',
        'path' => str_replace(__DIR__, '', $targetFile)
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al escribir el archivo exportado.'
    ]);
}
?>

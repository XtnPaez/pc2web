/* ============================================================
   pc2webmap - producer.js
   ------------------------------------------------------------
   Controla la lógica del modo PRODUCTOR:
   - Muestra/oculta herramientas según el modo (producer/user)
   - Gestiona carga de insumos (GeoJSON + SLD + metadata)
   - Ejecuta validación y exportación a modo usuario final.
   ------------------------------------------------------------
   Dependencias:
   - Leaflet 1.9.x
   - Bootstrap 5.x
   - map.js (debe inicializar window.map)
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  // ============================================================
  // Detectar modo de trabajo
  // ============================================================
  const isProducer = document.body.dataset.mode === 'producer';
  const tools = document.getElementById('producer-tools');

  // Mostrar u ocultar bloque de herramientas
  if (tools) tools.style.display = isProducer ? 'block' : 'none';

  // ============================================================
  // Activar botones solo en modo productor
  // ============================================================
  if (isProducer) {
    const btnUpload = document.getElementById('btn-upload');
    const btnStyle = document.getElementById('btn-style');
    const btnMetadata = document.getElementById('btn-metadata');
    const btnValidate = document.getElementById('btn-validate');
    const btnExport = document.getElementById('btn-export');

    // ----------------------------------------
    // Cargar archivo GeoJSON
    // ----------------------------------------
    if (btnUpload) btnUpload.addEventListener('click', () => {
      alert('📂 Seleccioná un archivo GeoJSON.\nSerá guardado en /data/input/');
      // En próximas versiones se usará un input[type=file] oculto + FormData para subir el archivo
      console.log('Acción pendiente: subida de GeoJSON a /data/input/');
    });

    // ----------------------------------------
    // Cargar archivo SLD
    // ----------------------------------------
    if (btnStyle) btnStyle.addEventListener('click', () => {
      alert('🎨 Seleccioná el archivo de estilo (.sld).\nDebe acompañar al GeoJSON.');
      console.log('Acción pendiente: subida de SLD a /data/input/');
    });

    // ----------------------------------------
    // Cargar metadata
    // ----------------------------------------
    if (btnMetadata) btnMetadata.addEventListener('click', () => {
      alert('🧾 Carga de metadata (opcional, formato JSON).');
      console.log('Acción pendiente: carga de metadata a /data/input/');
    });

    // ----------------------------------------
    // Ejecutar validación
    // ----------------------------------------
    if (btnValidate) btnValidate.addEventListener('click', () => {
      alert('🔍 Validación iniciada.\nEl validador procesará /data/input/ y creará /data/cache/tmp_YYYYMMDD_HHMMSS/');
      console.log('Ejecutando validación mediante src/core/validation.php...');
      // Futuro: llamada fetch('src/core/validation.php', { method:'POST' })
    });

    // ----------------------------------------
    // Exportar a modo usuario
    // ----------------------------------------
    if (btnExport) btnExport.addEventListener('click', exportToUserMode);
  }

  // ============================================================
  // Contenedor de capas validadas (solo modo productor)
  // ============================================================
  const sidebar = document.getElementById('sidebar');
  if (sidebar && !document.getElementById('layer-list')) {
    const layerListContainer = document.createElement('div');
    layerListContainer.id = 'layer-list';
    layerListContainer.classList.add('p-3');
    sidebar.appendChild(layerListContainer);
  }
});

/* ============================================================
   FUNCIÓN: exportToUserMode()
   ------------------------------------------------------------
   Genera una copia limpia del HTML actual (sin menú productor)
   y la guarda en /build/export/index.html mediante exporter.php.
   ============================================================ */
function exportToUserMode() {
  console.log('🚀 Iniciando exportación a modo usuario...');

  // Clonar el documento completo
  const clone = document.documentElement.cloneNode(true);

  // Eliminar herramientas del productor
  const toolsClone = clone.querySelector('#producer-tools');
  if (toolsClone) toolsClone.remove();

  // Cambiar atributo de modo
  const bodyClone = clone.querySelector('body');
  if (bodyClone) bodyClone.dataset.mode = 'user';

  // Serializar el HTML
  const htmlExport = '<!DOCTYPE html>\n' + clone.outerHTML;

  // Enviar al script PHP para guardar
  fetch('exporter.php', {
    method: 'POST',
    headers: { 'Content-Type': 'text/html' },
    body: htmlExport
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'ok') {
        alert('✅ Proyecto exportado correctamente.\nArchivo: /build/export/index.html');
        console.log('📦 Exportación completada:', data);
      } else {
        alert('⚠️ Error en exportación: ' + (data.message || 'Error desconocido.'));
        console.error('Error:', data);
      }
    })
    .catch(err => {
      alert('❌ Error al exportar: ' + err.message);
      console.error('Error de red:', err);
    });
}

// =============================================
// pc2webmap - producer.js
// ---------------------------------------------
// Control general del modo PRODUCTOR:
//   - Ejecuta la validación de insumos (GeoJSON + SLD)
//   - Carga las capas validadas en el panel izquierdo
//   - Gestiona la configuración de popups (más adelante)
// =============================================


// =====================================================
// 1. INICIALIZACIÓN DEL BOTÓN "VALIDAR CAPAS"
// =====================================================
document.addEventListener('DOMContentLoaded', () => {
  const btnValidate = document.getElementById('btnValidate');
  if (!btnValidate) return;

  btnValidate.addEventListener('click', () => {
    ejecutarValidacion();
  });
});


// =====================================================
// 2. FUNCIÓN PRINCIPAL: ejecutar validación de insumos
// -----------------------------------------------------
// Llama a src/core/validate.php, que:
//   - Verifica la estructura de los archivos en /data/input
//   - Crea carpeta temporal en /data/cache/tmp_<fecha>/
//   - Copia los archivos validados (.geojson / .sld)
//   - Genera el descriptor layers.json
// Si la validación es correcta, se llama a loadAvailableLayers()
// =====================================================
function ejecutarValidacion() {
  console.log("🧭 Iniciando validación de capas...");

  fetch('src/core/validate.php')
    .then(response => response.json())
    .then(result => {
      if (result.status === "ok") {
        console.log("✅ Validación exitosa:", result);

        // Extraer nombre de carpeta temporal
        const cacheFolder = result.path.replace("data/cache/", "");

        // Cargar las capas en el panel izquierdo
        loadAvailableLayers(cacheFolder);

        // Notificación visual breve
        showToast("Validación exitosa", "Las capas fueron validadas correctamente.");
      } else {
        console.warn("❌ Errores de validación:", result.errors);
        showToast("Error de validación", result.errors.join(" | "));
      }
    })
    .catch(err => {
      console.error("⚠️ Error ejecutando validación:", err);
      showToast("Error inesperado", "No se pudo validar las capas.");
    });
}


// =====================================================
// 3. FUNCIÓN AUXILIAR: mostrar notificaciones flotantes
// -----------------------------------------------------
// Usa toasts de Bootstrap para informar el resultado
// de la validación o errores al productor.
// =====================================================
function showToast(title, message) {
  // Crear contenedor de toasts si no existe
  if (!document.getElementById('toastContainer')) {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'position-fixed top-0 end-0 p-3';
    container.style.zIndex = 2000;
    document.body.appendChild(container);
  }

  // HTML del toast
  const toastHTML = `
    <div class="toast align-items-center text-white bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          <strong>${title}</strong><br>${message}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
      </div>
    </div>`;

  // Insertar y mostrar
  const container = document.getElementById('toastContainer');
  container.insertAdjacentHTML('beforeend', toastHTML);

  const toastElement = container.lastElementChild;
  const bsToast = new bootstrap.Toast(toastElement, { delay: 3500 });
  bsToast.show();

  // Eliminar el toast automáticamente al cerrarse
  toastElement.addEventListener('hidden.bs.toast', () => {
    toastElement.remove();
  });
}


// =====================================================
// 4. FUTURO: gestión de configuración de popups
// -----------------------------------------------------
// En versiones siguientes, aquí se implementará el flujo
// que permite al productor definir qué atributos mostrar
// en cada capa (popup_config.json).
// =====================================================

// Ejemplo de estructura prevista:
// function openPopupConfigModal(layerName, fields) { ... }
// function savePopupConfig(layerName, selectedFields) { ... }

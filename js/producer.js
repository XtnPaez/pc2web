// js/producer.js
// Control del modo productor: configuración de proyecto y subida de capas
// -------------------------------------------------------------

document.addEventListener('DOMContentLoaded', () => {
  // Botón: Nombre del proyecto
  const btnProject = document.getElementById('btn-project');
  if (btnProject) btnProject.addEventListener('click', showProjectModal);

  // Botón: Subir capa
  const btnUpload = document.getElementById('btn-upload');
  if (btnUpload) btnUpload.addEventListener('click', showUploadModal);
});

// =============================================================
// Modal de configuración del proyecto
// =============================================================
function showProjectModal() {
  const html = `
  <div class="modal fade" id="projectModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Configuración del proyecto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nombre del proyecto (máx. 24 caracteres)</label>
            <input type="text" class="form-control" id="projectNameInput" maxlength="24" placeholder="Ej: Mapa Social">
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="customFooterChk">
            <label class="form-check-label">Personalizar footer</label>
          </div>
          <div class="mb-3">
            <label class="form-label">Texto del footer (máx. 48 caracteres)</label>
            <input type="text" class="form-control" id="footerInput" maxlength="48" placeholder="Ej: Fuente: Observatorio 2025" disabled>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" id="saveProjectBtn" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </div>
  </div>`;
  document.body.insertAdjacentHTML('beforeend', html);

  const modalEl = document.getElementById('projectModal');
  const modal = new bootstrap.Modal(modalEl);
  modal.show();

  // Habilitar/deshabilitar input del footer
  const chk = modalEl.querySelector('#customFooterChk');
  const footerInput = modalEl.querySelector('#footerInput');
  chk.addEventListener('change', () => {
    footerInput.disabled = !chk.checked;
    if (!chk.checked) footerInput.value = '';
  });

  // Guardar configuración temporal (solo en memoria)
  modalEl.querySelector('#saveProjectBtn').addEventListener('click', () => {
    const name = modalEl.querySelector('#projectNameInput').value.trim();
    if (name) {
      document.getElementById('projectName').textContent = name;
    }
    // No se guarda en archivo; se usará solo durante exportación
    modal.hide();
    modalEl.remove();
  });

  modalEl.addEventListener('hidden.bs.modal', () => modalEl.remove());
}

// =============================================================
// Modal de subida de capa
// =============================================================
function showUploadModal() {
  const html = `
  <div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Subir nueva capa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="uploadForm" enctype="multipart/form-data">
            <div class="mb-3">
              <label class="form-label">Archivo GeoJSON</label>
              <input type="file" class="form-control" id="geojsonFile" name="geojsonFile" accept=".geojson" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Archivo SLD</label>
              <input type="file" class="form-control" id="sldFile" name="sldFile" accept=".sld" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Nombre de la capa</label>
              <input type="text" class="form-control" id="layerName" name="layerName" maxlength="60" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Descripción de la capa</label>
              <textarea class="form-control" id="layerDesc" name="layerDesc" rows="3" maxlength="500"></textarea>
            </div>
          </form>
          <div id="uploadMessage" class="small mt-2"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" id="submitUpload" class="btn btn-primary">Subir</button>
        </div>
      </div>
    </div>
  </div>`;
  document.body.insertAdjacentHTML('beforeend', html);

  const modalEl = document.getElementById('uploadModal');
  const modal = new bootstrap.Modal(modalEl);
  modal.show();

  modalEl.querySelector('#submitUpload').addEventListener('click', () => handleUpload(modalEl, modal));
  modalEl.addEventListener('hidden.bs.modal', () => modalEl.remove());
}

// =============================================================
// Envío de archivos y metadatos al servidor
// =============================================================
async function handleUpload(modalEl, modal) {
  const geojson = document.getElementById('geojsonFile').files[0];
  const sld = document.getElementById('sldFile').files[0];
  const name = document.getElementById('layerName').value.trim();
  const desc = document.getElementById('layerDesc').value.trim();
  const msg = document.getElementById('uploadMessage');

  if (!geojson || !sld || !name) {
    msg.textContent = 'Debe completar los campos obligatorios.';
    msg.className = 'text-danger';
    return;
  }

  const maxSize = 100 * 1024 * 1024;
  if (geojson.size > maxSize || sld.size > maxSize) {
    msg.textContent = 'Los archivos no deben superar los 100 MB.';
    msg.className = 'text-danger';
    return;
  }

  const base1 = geojson.name.split('.')[0];
  const base2 = sld.name.split('.')[0];
  if (base1 !== base2) {
    msg.textContent = 'Los nombres base deben coincidir.';
    msg.className = 'text-warning';
    return;
  }

  const formData = new FormData();
  formData.append('geojsonFile', geojson);
  formData.append('sldFile', sld);
  formData.append('layerName', name);
  formData.append('layerDesc', desc);

  try {
    const res = await fetch('upload.php', { method: 'POST', body: formData });
    const result = await res.json();
    msg.textContent = result.message;
    msg.className = result.status === 'success' ? 'text-success' : 'text-danger';
    if (result.status === 'success') {
      setTimeout(() => { modal.hide(); modalEl.remove(); }, 1500);
    }
  } catch (err) {
    msg.textContent = 'Error al subir capa.';
    msg.className = 'text-danger';
  }
}

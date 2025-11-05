// ============================================================
// js/producer.js
// ------------------------------------------------------------
// Control de flujo del modo productor pc2web
// - Manejo de modales "Nombre del proyecto" y "Subir capa"
// - Variables de sesión: nombreProyecto, footerTexto, footerCustom, layers[]
// - Subida de archivos a /src/core/upload.php
// - Validación en /src/core/validation.php
// - Render de lista de capas y flags de validación
// ============================================================

(() => {

  // ------------------------------------------------------------
  // Estado de sesión
  // ------------------------------------------------------------
  const state = {
    nombreProyecto: '',
    footerCustom: false,
    footerTexto: '',
    // layers: [{ id, baseName, geojson, sld, nombre, descripcion, validated }]
    layers: []
  };
  window.pc2webState = state; // visible para otros scripts

  // ------------------------------------------------------------
  // Elementos de la interfaz
  // ------------------------------------------------------------
  const el = {
    np: document.getElementById('nombreProyecto'),
    npCount: document.getElementById('npCount'),
    footerCustom: document.getElementById('footerCustom'),
    ft: document.getElementById('footerTexto'),
    ftCount: document.getElementById('ftCount'),
    saveProject: document.getElementById('saveProjectInfo'),

    uploadForm: document.getElementById('uploadForm'),
    fileGeo: document.getElementById('fileGeo'),
    fileSld: document.getElementById('fileSld'),
    nombreCapa: document.getElementById('nombreCapa'),
    descripcionCapa: document.getElementById('descripcionCapa'),
    uploadHint: document.getElementById('uploadHint'),

    layerList: document.getElementById('layerList'),

    btnValidate: document.getElementById('btnValidate'),
    btnExport: document.getElementById('btnExport'),
    btnPopup: document.getElementById('btnPopup')
  };

  const MAX_BYTES = 100 * 1024 * 1024; // límite 100 MB

  // ------------------------------------------------------------
  // Utilidades
  // ------------------------------------------------------------
  function basenameNoExt(filename = '') {
    const dot = filename.lastIndexOf('.');
    return dot > 0 ? filename.slice(0, dot) : filename;
  }

  function uiSetHint(msg, type = 'secondary') {
    el.uploadHint.textContent = msg;
    el.uploadHint.className = `alert alert-${type} py-2`;
    el.uploadHint.style.display = msg ? 'block' : 'none';
  }

  function renderLayerList() {
    el.layerList.innerHTML = '';
    state.layers.forEach((ly, idx) => {
      const id = `layer_chk_${idx}`;
      const wrap = document.createElement('div');
      wrap.className = 'form-check mb-1';

      const input = document.createElement('input');
      input.type = 'checkbox';
      input.className = 'form-check-input';
      input.id = id;
      input.disabled = !ly.validated;
      input.addEventListener('change', () => {
        // Aquí se integrará la visibilidad de capas en el mapa (Issue 5)
      });

      const label = document.createElement('label');
      label.className = 'form-check-label';
      label.setAttribute('for', id);
      label.textContent = `${ly.nombre} ${ly.validated ? '' : '(pendiente de validación)'}`;

      wrap.appendChild(input);
      wrap.appendChild(label);
      el.layerList.appendChild(wrap);
    });

    el.btnExport.disabled = !state.layers.some(l => l.validated);
    el.btnPopup.disabled = !state.layers.some(l => l.validated);
  }

  // ------------------------------------------------------------
  // Modal A: Nombre del proyecto / Footer
  // ------------------------------------------------------------
  function attachCounters() {
    const upd = () => {
      el.npCount.textContent = el.np.value.length;
      el.ftCount.textContent = el.ft.value.length;
    };
    el.np.addEventListener('input', upd);
    el.ft.addEventListener('input', upd);
    upd();

    // Habilitar/deshabilitar input footer según checkbox
    el.footerCustom.addEventListener('change', () => {
      el.ft.disabled = !el.footerCustom.checked;
      if (!el.footerCustom.checked) {
        el.ft.value = '';
        el.ftCount.textContent = '0';
      }
    });
  }

  // Guardar configuración del proyecto
  el.saveProject?.addEventListener('click', () => {
    state.nombreProyecto = el.np.value.trim();
    state.footerCustom = el.footerCustom.checked;
    state.footerTexto = el.footerCustom ? el.ft.value.trim() : '';
  });

  // ------------------------------------------------------------
  // Modal B: Subida de capa
  // ------------------------------------------------------------
  el.uploadForm?.addEventListener('submit', async (ev) => {
    ev.preventDefault();
    uiSetHint('');

    const fGeo = el.fileGeo.files[0];
    const fSld = el.fileSld.files[0];
    const nombre = el.nombreCapa.value.trim();
    const desc = el.descripcionCapa.value.trim();

    // Validaciones básicas
    if (!fGeo || !fSld) return uiSetHint('Debe seleccionar ambos archivos: GeoJSON y SLD.', 'warning');
    if (fGeo.size > MAX_BYTES || fSld.size > MAX_BYTES) return uiSetHint('Alguno de los archivos supera 100 MB.', 'danger');

    const bGeo = basenameNoExt(fGeo.name);
    const bSld = basenameNoExt(fSld.name);
    if (bGeo !== bSld) return uiSetHint(`Los nombres base no coinciden: "${bGeo}" vs "${bSld}".`, 'warning');
    if (!nombre || !desc) return uiSetHint('Complete nombre y descripción de la capa.', 'warning');

    const form = new FormData();
    form.append('geojson', fGeo);
    form.append('sld', fSld);
    form.append('nombre_capa', nombre);
    form.append('descripcion_capa', desc);

    try {
      uiSetHint('Subiendo capa...', 'secondary');
      const res = await fetch('src/core/upload.php', { method: 'POST', body: form });
      const data = await res.json();

      if (!data?.success) return uiSetHint(data?.error || 'Error al subir la capa.', 'danger');

      // Registrar capa en el estado de sesión
      state.layers.push({
        id: crypto.randomUUID(),
        baseName: bGeo,
        geojson: fGeo.name,
        sld: fSld.name,
        nombre,
        descripcion: desc,
        validated: false
      });

      renderLayerList();
      uiSetHint('Capa subida correctamente. Pendiente de validación.', 'success');
      el.uploadForm.reset();
    } catch {
      uiSetHint('Error de red al subir la capa.', 'danger');
    }
  });

  // ------------------------------------------------------------
  // Validar capas pendientes
  // ------------------------------------------------------------
  el.btnValidate?.addEventListener('click', async () => {
    // 1. Obtener lista real del servidor
    let serverUploads = [];
    try {
      const r = await fetch('src/core/list_uploads.php');
      const j = await r.json();
      serverUploads = Array.isArray(j?.uploads) ? j.uploads : [];
    } catch {
      alert('No se pudo sincronizar con el servidor.');
      return;
    }

    // 2. Remover capas cuyos archivos ya no existen
    const existsSet = new Set(serverUploads.map(u => u.basename));
    const before = state.layers.length;
    state.layers = state.layers.filter(ly => existsSet.has(ly.baseName));
    const removed = before - state.layers.length;
    if (removed > 0) renderLayerList();

    // 3. Detectar pendientes
    const pend = state.layers.filter(l => !l.validated);
    if (pend.length === 0) {
      alert('No hay capas pendientes de validar.');
      return;
    }

    // 4. Enviar validación al servidor
    try {
      const res = await fetch('src/core/validation.php', { method: 'POST' });
      const data = await res.json();

      if (!data?.success) {
        alert(data?.error || 'Error en validación.');
        return;
      }

      const ok = new Set(data.validated || []);
      const miss = new Set(data.missing || []);

      state.layers.forEach(l => {
        if (ok.has(l.baseName)) l.validated = true;
      });
      if (miss.size > 0) state.layers = state.layers.filter(l => !miss.has(l.baseName));

      renderLayerList();
      alert(`Validación completa. Capas validadas: ${ok.size}.`);
    } catch {
      alert('Fallo de red al validar.');
    }
  });

  // ------------------------------------------------------------
  // Inicialización
  // ------------------------------------------------------------
  attachCounters();
  renderLayerList();

})();

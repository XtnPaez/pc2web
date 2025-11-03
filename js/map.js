// ===============================================================
// map.js - inicialización del mapa Leaflet + capas dinámicas
// ===============================================================

// Inicializa el mapa
const map = L.map('map', {
  center: [-38.4161, -63.6167],
  zoom: 5,
  zoomControl: true,
});

// Capas base
const baseOSM = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

const baseArgenmap = L.tileLayer(
  'https://wms.ign.gob.ar/geoserver/gwc/service/tms/1.0.0/capabaseargenmap@EPSG%3A3857@png/{z}/{x}/{-y}.png',
  { attribution: '&copy; IGN Argentina' }
);

const baseMaps = {
  "OpenStreetMap": baseOSM,
  "Argenmap": baseArgenmap
};

const overlayMaps = {};
L.control.layers(baseMaps, overlayMaps).addTo(map);

// ===============================================================
// Carga dinámica de capas desde /data/cache/
// ===============================================================

async function loadAvailableLayers() {
  try {
    const response = await fetch('src/core/list_layers.php');
    const layers = await response.json();

    // Verifica que la respuesta sea un array válido
    if (!Array.isArray(layers)) {
      console.warn('⚠️ Respuesta inesperada de list_layers.php:', layers);
      const panel = document.getElementById('layer-list');
      if (panel) panel.innerHTML = '<p class="text-muted text-center mt-3">No hay capas disponibles.</p>';
      return;
    }

    const panel = document.getElementById('layer-list');
    if (!panel) {
      console.warn('⚠️ No se encontró el contenedor #layer-list.');
      return;
    }

    panel.innerHTML = ''; // Limpia listado anterior

    // Genera lista de capas
    layers.forEach(layer => {
      const div = document.createElement('div');
      div.classList.add('form-check', 'mb-1');

      const checkbox = document.createElement('input');
      checkbox.type = 'checkbox';
      checkbox.classList.add('form-check-input');
      checkbox.id = 'chk_' + layer.name;
      checkbox.dataset.geojson = layer.geojson;
      checkbox.dataset.sld = layer.sld;

      const label = document.createElement('label');
      label.classList.add('form-check-label');
      label.textContent = layer.metadata?.title || layer.name;

      div.appendChild(checkbox);
      div.appendChild(label);
      panel.appendChild(div);

      // Evento de carga/descarga
      let geojsonLayer = null;
      checkbox.addEventListener('change', async () => {
        if (checkbox.checked) {
          const res = await fetch(layer.geojson);
          const data = await res.json();

          // Estilo genérico (parser real será en Issue 5)
          const styleFunc = { color: '#0078A8', weight: 2 };

          geojsonLayer = L.geoJSON(data, { style: styleFunc }).addTo(map);
          overlayMaps[layer.name] = geojsonLayer;
        } else {
          if (geojsonLayer) {
            map.removeLayer(geojsonLayer);
            delete overlayMaps[layer.name];
          }
        }
      });
    });
  } catch (error) {
    console.error('Error cargando capas:', error);
  }
}

// Ejecutar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', loadAvailableLayers);

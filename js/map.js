// =============================================
// pc2webmap - map.js
// ---------------------------------------------
// Inicialización del mapa principal y gestión
// de capas validadas por el productor.
//
// En este flujo, los mapas base (Argenmap / OSM)
// se manejan con el control de Leaflet estándar,
// mientras que las capas validadas se listan
// en el panel izquierdo (#layer-list).
// =============================================


// =====================================================
// 1. CONFIGURACIÓN BASE DEL MAPA
// =====================================================

// Crear el mapa centrado en Argentina
const map = L.map('map', {
  center: [-38.4, -63.6],
  zoom: 4
});

// --- Capas base ---
const argenmap = L.tileLayer(
  'https://wms.ign.gob.ar/geoserver/gwc/service/tms/1.0.0/capabaseargenmap@EPSG%3A3857@png/{z}/{x}/{-y}.png',
  { attribution: '&copy; IGN - Argenmap' }
).addTo(map);

const osm = L.tileLayer(
  'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
  { attribution: '&copy; OpenStreetMap contributors' }
);

// Control de mapas base (solo baseMaps, sin capas validadas)
const baseMaps = { "Argenmap": argenmap, "OpenStreetMap": osm };
L.control.layers(baseMaps, null, { collapsed: true }).addTo(map);

// Diccionario global para almacenar las capas validadas
const overlayMaps = {};


// =====================================================
// 2. CACHE LOCAL DE POPUPS (popup_config.json)
// =====================================================
const popupConfigs = {}; // Guarda la configuración por capa


// =====================================================
// 3. FUNCIÓN PRINCIPAL: cargar capas validadas
// -----------------------------------------------------
// Lee el archivo layers.json generado en /data/cache/tmp_<fecha>/
// y crea los checkboxes correspondientes en el panel izquierdo.
// =====================================================
function loadAvailableLayers(cacheFolder) {
  console.log("📂 Cargando capas desde carpeta:", cacheFolder);
  window.currentCacheFolder = cacheFolder; // Guardamos referencia global

  // Leer el descriptor de capas
  fetch(`data/cache/${cacheFolder}/layers.json`)
    .then(response => {
      if (!response.ok) throw new Error("No se encontró layers.json");
      return response.json();
    })
    .then(layers => {
      // Aceptar tanto array como objeto único
      const layerArray = Array.isArray(layers) ? layers : [layers];
      console.log("Capas encontradas en layers.json:", layerArray);

      // Obtener el panel lateral y limpiar su contenido previo
      const panel = document.getElementById('layer-list');
      panel.innerHTML = '';

      // Crear una capa Leaflet por cada entrada en layers.json
      layerArray.forEach(layerInfo => {
        if (!layerInfo.geojson) {
          console.warn(`⚠️ La capa ${layerInfo.name} no tiene ruta GeoJSON válida.`);
          return;
        }

        // Cargar el archivo GeoJSON correspondiente
        fetch(layerInfo.geojson)
          .then(res => {
            if (!res.ok) throw new Error(`No se encontró ${layerInfo.geojson}`);
            return res.json();
          })
          .then(data => {
            console.log(`✅ Capa "${layerInfo.name}" cargada (${data.features.length} features)`);

            // Crear la capa Leaflet
            const layer = L.geoJSON(data, {
              onEachFeature: (feature, lyr) => {
                // Aplicar popups si existe configuración
                applyPopupToFeature(layerInfo.name, feature, lyr);
              }
            });

            // Guardar referencia global para poder activarla/desactivarla
            overlayMaps[layerInfo.name] = layer;

            // Crear checkbox en el panel izquierdo
            const div = document.createElement('div');
            div.classList.add('form-check', 'mb-1');
            div.innerHTML = `
              <input class="form-check-input" type="checkbox" id="chk_${layerInfo.name}">
              <label class="form-check-label" for="chk_${layerInfo.name}">
                ${layerInfo.name}
              </label>
            `;
            panel.appendChild(div);

            // Manejar activación/desactivación desde el checkbox
            const chk = document.getElementById(`chk_${layerInfo.name}`);
            chk.addEventListener('change', (e) => {
              if (e.target.checked) {
                layer.addTo(map);
                console.log(`🟢 Capa "${layerInfo.name}" activada`);
              } else {
                map.removeLayer(layer);
                console.log(`🔴 Capa "${layerInfo.name}" desactivada`);
              }
            });
          })
          .catch(err => console.error(`Error cargando GeoJSON ${layerInfo.name}:`, err));
      });
    })
    .catch(err => console.error('Error leyendo layers.json:', err));
}


// =====================================================
// 4. FUNCIÓN: aplicar configuración de popups
// -----------------------------------------------------
// Lee el popup_config.json de la carpeta actual (una sola vez)
// y aplica la configuración a cada feature de la capa.
// =====================================================
function applyPopupToFeature(layerName, feature, layer) {
  // Si ya tenemos la configuración cargada, aplicarla directamente
  if (popupConfigs[layerName]) {
    bindPopupFromConfig(layerName, feature, layer);
    return;
  }

  // Ruta del archivo popup_config.json
  const configPath = `data/cache/${window.currentCacheFolder}/popup_config.json`;

  // Cargar solo una vez por capa
  fetch(configPath)
    .then(res => {
      if (!res.ok) throw new Error("popup_config.json no encontrado");
      return res.json();
    })
    .then(config => {
      popupConfigs[layerName] = config;
      bindPopupFromConfig(layerName, feature, layer);
    })
    .catch(() => {
      // Si no existe el archivo, registrar vacío para evitar fetch redundantes
      popupConfigs[layerName] = {};
    });
}


// =====================================================
// 5. FUNCIÓN AUXILIAR: vincular popup a una feature
// -----------------------------------------------------
// Crea el contenido HTML del popup según los campos
// seleccionados por el productor en popup_config.json.
// =====================================================
function bindPopupFromConfig(layerName, feature, layer) {
  const config = popupConfigs[layerName];
  if (config && config[layerName]) {
    const fields = config[layerName];
    const html = fields
      .map(f => `<strong>${f}:</strong> ${feature.properties[f] ?? ''}`)
      .join('<br>');
    layer.bindPopup(html);
  }
}

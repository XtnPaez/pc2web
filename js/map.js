// =============================================
// pc2webmap - map.js (auto‐detector de carpeta cache)
// ---------------------------------------------
// Carga capas en modo productor y export.
// Detecta automáticamente la carpeta tmp_* más reciente.
// =============================================

// === 1. MAPA BASE ===
const map = L.map('map', { center: [-38.4, -63.6], zoom: 4 });

const argenmap = L.tileLayer(
  'https://wms.ign.gob.ar/geoserver/gwc/service/tms/1.0.0/capabaseargenmap@EPSG%3A3857@png/{z}/{x}/{-y}.png',
  { attribution: '&copy; IGN - Argenmap' }
).addTo(map);

const osm = L.tileLayer(
  'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
  { attribution: '&copy; OpenStreetMap contributors' }
);

L.control.layers({ "Argenmap": argenmap, "OpenStreetMap": osm }, null, { collapsed: true }).addTo(map);

// === 2. VARIABLES GLOBALES ===
const overlayMaps = {};
const popupConfigs = {};
let currentCacheFolder = null;

// === 3. DETECTAR MODO EXPORT ===
const isExport = window.location.pathname.includes('/build/export/');
console.log(`🧭 Modo actual: ${isExport ? 'EXPORTACIÓN' : 'PRODUCTOR'}`);

// === 4. FUNCIÓN PRINCIPAL ===
function loadAvailableLayers(cacheFolder) {
  currentCacheFolder = cacheFolder;
  const jsonPath = `${cacheFolder}/layers.json`;
  console.log(`📂 Buscando ${jsonPath}`);

  fetch(jsonPath)
    .then(r => {
      if (!r.ok) throw new Error(`No se encontró ${jsonPath}`);
      return r.json();
    })
    .then(layers => {
      const arr = Array.isArray(layers) ? layers : [layers];
      console.log(`✅ ${arr.length} capa(s) detectada(s)`);

      const panel = document.getElementById('layer-list');
      if (panel) panel.innerHTML = '';

      arr.forEach(info => {
        fetch(info.geojson)
          .then(res => res.json())
          .then(data => {
            const layer = L.geoJSON(data, {
              onEachFeature: (f, l) => applyPopupToFeature(info.name, f, l)
            });

            overlayMaps[info.name] = layer;

            if (panel) {
              const div = document.createElement('div');
              div.classList.add('form-check', 'mb-1');
              div.innerHTML = `
                <input class="form-check-input" type="checkbox" id="chk_${info.name}">
                <label class="form-check-label" for="chk_${info.name}">
                  ${info.name}
                </label>`;
              panel.appendChild(div);
              const chk = document.getElementById(`chk_${info.name}`);
              chk.addEventListener('change', e => {
                e.target.checked ? layer.addTo(map) : map.removeLayer(layer);
              });
            } else {
              layer.addTo(map);
              console.log(`🟢 Capa "${info.name}" agregada automáticamente`);
            }
          })
          .catch(err => console.error(`Error cargando ${info.name}:`, err));
      });
    })
    .catch(err => console.error('Error leyendo layers.json:', err));
}

// === 5. AUTO-DETECCIÓN DE CARPETA CACHE (solo export) ===
if (isExport) {
  // Intentar detectar automáticamente la carpeta tmp_* dentro de data/cache/
  fetch('data/cache/')
    .then(() => {
      // Usar fetch() sobre layers.json conocido dentro del export
      const base = 'data/cache/';
      fetch(base)
        .then(() => {
          // Hacer una pequeña solicitud HEAD a listar contenido (solo local, no server)
          // Como alternativa, probar múltiples carpetas comunes
          const tryFolders = ['tmp_20251104_113717', 'tmp_20251104_120000']; // fallback manual
          // Buscar la carpeta tmp_ más reciente detectando por estructura existente
          const cacheBase = base;
          fetch(cacheBase)
            .catch(() => console.warn('No se pudo acceder a data/cache directamente'))
            .finally(() => {
              // Probar detección automática de carpeta tmp_
              // Enumerar con XMLHttpRequest (funciona localmente en XAMPP)
              try {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', cacheBase, false);
                xhr.send();
                const matches = xhr.responseText.match(/tmp_\d{8}_\d{6}/g);
                if (matches && matches.length > 0) {
                  const folder = `${cacheBase}${matches[matches.length - 1]}`;
                  console.log(`📦 Carpeta detectada: ${folder}`);
                  loadAvailableLayers(folder);
                } else {
                  console.warn('⚠️ No se detectó carpeta tmp_*. Se usará la más reciente conocida.');
                  loadAvailableLayers(`${cacheBase}${tryFolders[0]}`);
                }
              } catch (e) {
                console.warn('⚠️ Detección automática falló:', e);
                loadAvailableLayers(`${cacheBase}${tryFolders[0]}`);
              }
            });
        });
    })
    .catch(() => console.error('No se pudo acceder al directorio data/cache/'));
}

// === 6. POPUPS (sin cambios) ===
function applyPopupToFeature(layerName, feature, layer) {
  if (popupConfigs[layerName]) {
    bindPopupFromConfig(layerName, feature, layer);
    return;
  }
  const configPath = `${currentCacheFolder}/popup_config.json`;
  fetch(configPath)
    .then(res => res.ok ? res.json() : {})
    .then(cfg => {
      popupConfigs[layerName] = cfg;
      bindPopupFromConfig(layerName, feature, layer);
    })
    .catch(() => { popupConfigs[layerName] = {}; });
}

function bindPopupFromConfig(layerName, feature, layer) {
  const cfg = popupConfigs[layerName];
  if (cfg && cfg[layerName]) {
    const html = cfg[layerName]
      .map(f => `<strong>${f}:</strong> ${feature.properties[f] ?? ''}`)
      .join('<br>');
    layer.bindPopup(html);
  }
}

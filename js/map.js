// js/map.js
// Inicialización del mapa Leaflet con dos mapas base: Argenmap (WMS) y OSM (tiles).
// Incluye Layer Control y wiring para una futura lista de capas en el panel izquierdo.
// En esta etapa no se consumen datos reales: se dejan hooks para integrar layers.json del validador.

// ======= Estado y referencias DOM =======
let map;                           // Instancia Leaflet
let baseLayers = {};               // Capas base para el control
const overlayLayers = {};          // Capas vectoriales activables por checkbox (id -> L.GeoJSON/L.LayerGroup)
const legends = {};                // Leyendas por capa (id -> HTML string)
const layerListEl = document.getElementById('layerList');
const legendContainerEl = document.getElementById('legendContainer');

// ======= Inicialización del mapa =======
document.addEventListener('DOMContentLoaded', () => {
  initMap();
  // Hook de ejemplo: poblar lista de capas cuando tengamos layers.json
  // loadLayersFromCache(); // Se implementará en Issue 3/4

  // Ejemplo opcional: si quisieras ver un placeholder de capa, descomentar:
  // injectPlaceholderLayer();
});

// ======= Función principal para configurar mapa y bases =======
function initMap() {
  // Centro aproximado de Argentina
  map = L.map('map', {
    center: [-40.4161, -63.6167],
    zoom: 4,
    zoomControl: true,
  });

  // Capa base OSM (tiles)
  const osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap'
  });

  // Capa base Argenmap (WMS IGN)
  // Layer común: "capabaseargenmap"
  const argenmap = L.tileLayer('https://wms.ign.gob.ar/geoserver/gwc/service/tms/1.0.0/capabaseargenmap@EPSG%3A3857@png/{z}/{x}/{-y}.png', {
    minZoom: 1, 
    maxZoom: 20,  
    attribution: 'Argenmap-IGN'
  });
  argenmap.addTo(map);

  // Agregar ambas al control. Activamos OSM por defecto.
  baseLayers = {
    'OSM': osm,
    'Argenmap': argenmap
  };
  
  // Control de capas: bases y, más adelante, overlays
  L.control.layers(baseLayers, overlayLayers, { position: 'topright', collapsed: true }).addTo(map);

  // Corrige el tamaño inicial tras aplicar los offsets CSS
  setTimeout(() => map.invalidateSize(), 250);
}

// ======= Integración futura con layers.json =======
// Esta función será reemplazada para leer /data/cache/tmp_YYYY.../layers.json (Issue 3/4)
async function loadLayersFromCache() {
  // Ejemplo de dónde se obtendría el JSON activo:
  // const resp = await fetch('data/cache/<tmp_activa>/layers.json');
  // const cfg = await resp.json();
  // buildLayerList(cfg.layers);
}

// Construye la lista del panel izquierdo y crea capas Leaflet vacías por ahora
function buildLayerList(layersConfig) {
  // Limpieza previa
  layerListEl.innerHTML = '';
  for (const item of layersConfig) {
    // item.id, item.name, item.path, item.legendHTML, etc. según def. de validation.php
    const id = item.id;
    const label = item.name || id;

    // Crear entrada con checkbox
    const entry = document.createElement('label');
    entry.className = 'list-group-item';
    entry.innerHTML = `
      <input class="form-check-input me-1 layer-toggle" type="checkbox" data-layer-id="${id}">
      ${label}
    `;
    layerListEl.appendChild(entry);

    // Inicializar contenedores en memoria
    overlayLayers[id] = L.layerGroup(); // luego se reemplaza por L.geoJSON()
    legends[id] = item.legendHTML || `<div class="legend-box"><div class="legend-title">${label}</div><div>Sin estilo SLD aplicado</div></div>`;
  }

  // Delegación de eventos para toggles
  layerListEl.addEventListener('change', onLayerToggle);
}

// Maneja alta/baja de capas en mapa y su leyenda asociada
function onLayerToggle(e) {
  const tgt = e.target;
  if (!tgt.classList.contains('layer-toggle')) return;

  const layerId = tgt.getAttribute('data-layer-id');
  const active = tgt.checked;

  // Si no existe la capa aún, no hacemos nada
  if (!overlayLayers[layerId]) return;

  if (active) {
    // Agregar la capa al mapa
    overlayLayers[layerId].addTo(map);

    // Inyectar leyenda
    const box = document.createElement('div');
    box.className = 'legend-wrapper';
    box.setAttribute('data-legend-id', layerId);
    box.innerHTML = legends[layerId] || '';
    legendContainerEl.appendChild(box);
  } else {
    // Remover capa y su leyenda
    map.removeLayer(overlayLayers[layerId]);
    const toRemove = legendContainerEl.querySelector(`.legend-wrapper[data-legend-id="${layerId}"]`);
    if (toRemove) toRemove.remove();
  }
}

// ======= Placeholder opcional para probar UI antes de validation.php =======
function injectPlaceholderLayer() {
  // Simula 1 capa disponible
  const fakeCfg = {
    layers: [
      {
        id: 'ejemplo_deptos',
        name: 'Departamentos (ejemplo)',
        legendHTML: `
          <div class="legend-box">
            <div class="legend-title">Departamentos</div>
            <div>Relleno gris, borde oscuro</div>
          </div>
        `
      }
    ]
  };
  buildLayerList(fakeCfg.layers);

  // Capa vectorial ficticia: un polígono suelto en La Pampa
  const ejemploGeo = {
    "type": "FeatureCollection",
    "features": [{
      "type": "Feature",
      "properties": {"nombre":"Ejemplo"},
      "geometry": {
        "type": "Polygon",
        "coordinates": [[
          [-65.5,-38.5],[-65.0,-38.5],[-65.0,-38.0],[-65.5,-38.0],[-65.5,-38.5]
        ]]
      }
    }]
  };
  const lyr = L.geoJSON(ejemploGeo, {
    style: { color: "#333", weight: 1, fillColor: "#bbb", fillOpacity: 0.5 }
  });
  overlayLayers['ejemplo_deptos'] = lyr; // reemplazo del layerGroup vacío
}

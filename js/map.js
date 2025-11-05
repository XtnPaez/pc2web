// ============================================================
// js/map.js
// ------------------------------------------------------------
// Mapa principal con Argenmap + OSM
// Versión NINJA: robusta, limpia y con invalidateSize
// ============================================================

document.addEventListener('DOMContentLoaded', function () {

  // --- Crear mapa ---
  const map = L.map('map', {
    center: [-38.5, -64.5],
    zoom: 4,
    zoomControl: true
  });

  // --- Mapas base ---
  const argenmap = L.tileLayer(
    'https://wms.ign.gob.ar/geoserver/gwc/service/tms/1.0.0/capabaseargenmap@EPSG%3A3857@png/{z}/{x}/{-y}.png',
    {
      attribution: '&copy; <a href="https://www.ign.gob.ar" target="_blank">IGN</a>',
      minZoom: 1,
      maxZoom: 20,
      tms: true,
      detectRetina: true,
      errorTileUrl: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=='
    }
  );

  const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://openstreetmap.org" target="_blank">OpenStreetMap</a> contributors',
    maxZoom: 19,
    detectRetina: true
  });

  // --- Capa activa por defecto ---
  argenmap.addTo(map);

  // --- Control de capas ---
  const baseMaps = {
    "Argenmap (IGN)": argenmap,
    "OpenStreetMap": osm
  };

  L.control.layers(baseMaps, {}, {
    position: 'topright',
    collapsed: true
  }).addTo(map);

  // --- ¡CRUCIAL! Ajustar tamaño si el contenedor cambia ---
  setTimeout(() => {
    map.invalidateSize();
  }, 200);

  // Opcional: re-ajustar si hay resize de ventana
  window.addEventListener('resize', () => {
    setTimeout(() => map.invalidateSize(), 100);
  });

  // Exportar mapa globalmente (útil para otros scripts)
  window.pc2webMap = map;
});
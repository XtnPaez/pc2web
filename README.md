# Visualizador Web de Mapas

Generador de entregables HTML autónomos basados en archivos GeoJSON. Permite cargar, configurar y exportar mapas interactivos con Leaflet y Bootstrap.

## Características principales

- Carga de múltiples capas GeoJSON.
- Configuración de estilos por capa (color, opacidad, símbolos).
- Definición de popups con campos y etiquetas personalizadas.
- Generación de un paquete web listo para publicar con:
  - Navbar fijo (nombre, pestañas Mapa y Documentación).
  - Footer con fecha e institucional.
  - Panel lateral con capas, checkboxes y leyendas.
  - Mapa con bases Argenmap y OSM.
- Sin dependencias externas: librerías embebidas.

## Tecnologías

- **Frontend**: Leaflet.js + Bootstrap 5.
- **Backend (App de escritorio)**: sugerido en **Electron (Node.js)** o **Tauri (Rust/JS)**.
- **Mapas base**: Argenmap y OpenStreetMap.
- **Formato de salida**: HTML, CSS, JS embebidos.

## Uso esperado

1. Cargar GeoJSON.
2. Configurar estilo y popups.
3. Presionar **Generar** → exporta un paquete web con todo el mapa.

---

© 2025 - Proyecto de código abierto.

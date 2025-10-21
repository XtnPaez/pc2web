# Visualizador Web de Mapas

Aplicación de escritorio que genera entregables HTML autosuficientes a partir de GeoJSON y estilos exportados desde QGIS.  
Permite cargar capas, configurar popups y metadatos, y generar un visualizador web con interfaz moderna basada en Leaflet y Bootstrap.

## Características principales

- Carga de capas GeoJSON **ya validadas y estilizadas desde QGIS**.  
- Configuración de popups con campos seleccionados.  
- Generación automática de documentación a partir de la metadata cargada.  
- Entregable HTML con:
  - Navbar fijo (nombre, pestañas *Mapa* y *Documentación*).
  - Footer institucional con fecha.
  - Panel lateral con lista de capas, checkboxes y leyendas.
  - Mapa con bases **Argenmap** y **OpenStreetMap**.
- Librerías embebidas localmente (sin CDN).

## Tecnologías

- **Frontend**: Leaflet.js + Bootstrap 5.  
- **Backend (App de escritorio)**: recomendado **Electron (Node.js)** o **Tauri (Rust/JS)**.  
- **Mapas base**: Argenmap y OpenStreetMap.

## Flujo básico de uso

1. Cargar los GeoJSON y archivos de estilo exportados desde QGIS.  
2. Configurar popups y metadata.  
3. Visualizar la previsualización del mapa (idéntica al resultado final).  
4. Presionar **Generar** → se crea un paquete web completo y autosuficiente.

---

© 2025 - Proyecto de código abierto.

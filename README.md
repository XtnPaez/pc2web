# pc2web v1.2 — Noviembre 2025

## Descripción
**pc2web** convierte insumos GIS (GeoJSON + SLD) en visualizadores web estáticos.
El resultado es un **paquete HTML autosuficiente** que puede abrirse sin servidor.

## Características
- Flujo único del **productor** operado desde `index.php` con modales.
- Validación de capas y generación de `layers.json` en cache temporal.
- Configuración de popups por capa (`popup_config.json`).
- Exportación a `build/export/<proyecto>/` con assets JS/CSS.
- Mapas base: **Argenmap** y **OpenStreetMap**.
- Sin íconos PNG para marcadores; estilos geométricos por SLD/SVG.
- Backend **PHP crudo** + Frontend **JavaScript ES6**. UI **Bootstrap 5.x**, mapa **Leaflet 1.9.x**.

## Estructura mínima
```
pc2web/
├── index.php
├── js/
│   ├── map.js
│   └── producer.js
├── css/
│   └── custom.css
├── data/
│   ├── input/
│   │   └── uploads/        # subidas de sesión
│   └── cache/
├── build/
│   └── export/
├── exporter.php
└── src/core/validation.php
```

## Uso
1. Abrir `index.php`.
2. **Subir capa**: elegir `.geojson` + `.sld`, cargar nombre del proyecto y descripción.
3. **Validar capa**: crear cache `tmp_<timestamp>` y `layers.json`.
4. **Setear popup**: elegir campos visibles y etiquetas. Guarda `popup_config.json` por capa.
5. **Cargar otra capa** o **Exportar proyecto**.
6. Abrir `build/export/<proyecto>/index.html` directamente en el navegador.

## Resultado
```
build/export/<proyecto>/
├── index.html
└── assets/
    ├── map.js
    ├── producer.js
    └── custom.css
```

El brand del navbar del exportado mostrará el **Nombre del proyecto**.

## Licencia
2025 — Uso interno con atribución a **pc2web**.

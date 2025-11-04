# pc2web v1.2 — Noviembre 2025
## Documento funcional del sistema

### 1. Objetivo
Generar un **paquete HTML autosuficiente** a partir de capas GIS (GeoJSON + SLD),
sin necesidad de servidor. Todo el proceso se opera desde la interfaz del productor.

### 2. Flujo de trabajo del productor
1. **Subir capa** (modal):
   - Seleccionar `.geojson` y `.sld`.
   - Completar **Nombre del proyecto** y **Descripción**.
   - La subida se guarda en `/data/input/uploads/`.
2. **Validar capa**:
   - Ejecuta `src/core/validation.php`.
   - Crea `/data/cache/tmp_<timestamp>/` con `layers.json`.
   - Habilita “Setear popup”.
3. **Setear popup** (modal):
   - Lista campos del GeoJSON.
   - Para cada campo: checkbox “mostrar” + campo “Etiqueta”.
   - Guarda `popup_config.json` **por capa** dentro de la cache activa.
4. **Cargar otra capa** o **Exportar**:
   - Cargar otra capa: regresa al paso 1 y conserva la cache del proyecto.
   - Exportar: crea `/build/export/<proyecto>/` y limpia `/data/input/` + `/data/cache/`.

### 3. Resultado de exportación
Estructura final por proyecto:
```
build/export/<proyecto>/
├── index.html
└── assets/
    ├── map.js
    ├── producer.js
    └── custom.css
```
- El **brand** del navbar se reemplaza por el **Nombre del proyecto**.
- `index.html` funciona sin servidor.

### 4. Componentes
- **index.php**: interfaz única del flujo (modales y controles).
- **js/map.js**: inicializa Leaflet, bases Argenmap/OSM y carga capas validadas.
- **js/producer.js**: estados del flujo, subida/validación/exportación por fetch.
- **src/core/validation.php**: verifica pares `.geojson` + `.sld`, arma `layers.json`.
- **exporter.php**: empaqueta, ajusta rutas y limpia carpetas tras exportar.

### 5. Convenciones
- Backend **PHP crudo**. Frontend **JS ES6** puro.
- UI con **Bootstrap 5.x**. Mapas con **Leaflet 1.9.x**.
- Código **siempre bien comentado**.
- Sin PNGs de marcadores. Puntos como figuras geométricas via SLD/SVG.
- Mapas base oficiales: **Argenmap** y **OpenStreetMap**.

### 6. Validaciones y errores
- Rechazar capas sin su `.sld`.
- Reportar errores detallados en JSON y toasts de UI.
- Evitar rutas absolutas en archivos exportados.

### 7. Próximos pasos
- Editor visual de popups.
- Vista previa de estilos SLD.
- Soporte para metadatos extensibles por capa y por proyecto.

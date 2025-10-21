# pc2web

**pc2web** es una herramienta local desarrollada en **PHP + HTML + JavaScript** que permite generar un visualizador web autosuficiente (HTML, Leaflet, Bootstrap) a partir de archivos **GeoJSON**, estilos exportados desde **QGIS** y metadata adicional por capa.

No requiere instalación de dependencias externas ni permisos de administrador: se ejecuta desde un **entorno PHP portable**.

## 🚀 Objetivo

Facilitar la creación rápida de un visualizador web completo, listo para compartir, a partir de capas exportadas desde QGIS, sin intervención manual sobre el código.

## ⚙️ Características principales

- Carga de **archivos GeoJSON**, estilos y metadata mediante formulario.
- Visualización previa del mapa en plantilla Bootstrap + Leaflet.
- Permite agregar múltiples capas y previsualizarlas.
- Botón **“Generar proyecto”** que exporta la estructura HTML final.
- Botón **“Reset”** que limpia la sesión para iniciar un nuevo proyecto.
- Interfaz simple y ligera, ejecutable en cualquier entorno con PHP.

## 🧩 Requisitos

- **PHP Portable 8.x** o superior.  
- **Navegador web moderno** (Chrome, Edge o Firefox).  
- No requiere conexión a internet ni instalación adicional.

## 📁 Estructura del proyecto

```
├── php/
│   └── php.exe (versión portable)
├── www/pc2web
│   ├── index.php
│   ├── inc/
│   │   ├── upload.php
│   │   ├── render.php
│   │   ├── export.php
│   │   └── reset.php
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css
│   │   ├── js/
│   │   │   ├── app.js
│   │   │   └── map.js
│   │   └── img/
│   ├── templates/
│   │   ├── layout.html
│   │   ├── layer_card.html
│   │   └── popup.html
│   └── export/
│       └── (proyectos generados)
└── start.bat
```

## ▶️ Cómo ejecutar

1. Descomprimir el paquete completo en una carpeta.  
2. Ejecutar el archivo `start.bat`.  
3. Se abrirá automáticamente el navegador en  
   **http://localhost:8080**  
4. Usar la interfaz para:
   - Cargar capas (GeoJSON + estilo + metadata)
   - Visualizarlas en el mapa
   - Generar el proyecto final (HTML autosuficiente)

## 🧱 Estructura del export final

Cada proyecto generado incluirá:

```
mi_proyecto/
├── index.html
├── assets/
│   ├── leaflet/
│   ├── bootstrap/
│   ├── css/
│   └── js/
├── data/
│   └── capas.geojson
└── metadata.json
```

## 🧰 Créditos técnicos

- **Bootstrap 5** para el layout.
- **Leaflet** para el mapa interactivo.
- **PHP Portable** como backend local sin instalación.
- **QGIS** como fuente de los GeoJSON y estilos.

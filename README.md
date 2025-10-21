# pc2web

**Generador de visualizadores web desde QGIS**

## Propósito
El proyecto **pc2web** permite transformar capas exportadas desde QGIS (en formato GeoJSON) en visualizadores web HTML interactivos utilizando **Leaflet** y **Bootstrap**.  
Su objetivo es simplificar la publicación y difusión de información geográfica mediante una aplicación liviana, portable y autosuficiente.

## Alcance
- Entorno local, sin instalación ni dependencias externas.  
- Compatible tanto con **PHP Portable** como con **XAMPP**.  
- Estructura unificada en todas las máquinas:  
  `C:\Users\cpaez\devstack\www\pc2web`  

## Características principales
- Carga de una o múltiples capas GeoJSON con estilos.  
- Previsualización directa sobre Leaflet.  
- Generación de un paquete HTML exportable y autosuficiente.  
- Soporte para metadatos y configuración visual.  
- Sin conexión a bases de datos ni servidores externos.

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

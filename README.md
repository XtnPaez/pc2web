# pc2web

**Generador de visualizadores web desde QGIS**

## Propósito
El proyecto **pc2web** permite transformar capas exportadas desde QGIS (en formato GeoJSON) en visualizadores web HTML interactivos utilizando **Leaflet** y **Bootstrap**.  
Su objetivo es simplificar la publicación y difusión de información geográfica mediante una aplicación liviana, portable y autosuficiente.

## Alcance
- Entorno local, sin instalación ni dependencias externas.  
- Compatible tanto con **PHP Portable** como con **XAMPP**.  
- Estructura unificada en todas las máquinas:  
  `...\pc2web`  

## Características principales
- Carga de una o múltiples capas GeoJSON con estilos.  
- Previsualización directa sobre Leaflet.  
- Generación de un paquete HTML exportable y autosuficiente.  
- Soporte para metadatos y configuración visual.  
- Sin conexión a bases de datos ni servidores externos.

## Tecnologías
- **Frontend:** Bootstrap 5, Leaflet 1.9.x, JS.  
- **Backend:** PHP plano (portable o XAMPP).  
- **Estructura de salida:** archivos estáticos HTML, CSS y JS.

## Estado del proyecto
Primera versión estable para entorno local unificado (multi-PC).  
Se prevé evolución hacia un generador modular de componentes y estilos temáticos.

---
© 2025 Cristian Páez | Proyecto pc2web

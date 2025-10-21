# Documento técnico del proyecto pc2web

## 1. Introducción
El proyecto **pc2web** surge para automatizar la generación de visualizadores web a partir de capas exportadas desde QGIS, reduciendo al mínimo el trabajo manual requerido para publicar mapas en HTML.

La aplicación se ejecuta de forma **local**, sin requerir instalación, y puede correrse tanto mediante **PHP Portable** (desde cualquier carpeta) como dentro de un entorno **XAMPP** (`htdocs/pc2web`).

## 2. Arquitectura general

**Tipo de aplicación:** Web local, autosuficiente  
**Frontend:** HTML + Bootstrap + Leaflet  
**Backend:** PHP plano  
**Almacenamiento:** Archivos temporales y carpeta `/export` para proyectos generados

### Estructura de carpetas

pc2web/
├── php/                → PHP portable (si aplica)
├── www/
│   ├── index.php       → interfaz principal
│   ├── inc/            → scripts PHP auxiliares
│   ├── assets/         → CSS, JS, íconos
│   ├── templates/      → plantillas HTML
│   └── export/         → salida de proyectos generados

## 3. Flujo de funcionamiento

1. **Carga de capa:** el usuario selecciona un archivo GeoJSON y su configuración.  
2. **Visualización:** se renderiza la capa en Leaflet con su simbología.  
3. **Configuración:** se permite editar título, metadatos, estilo, colores, etc.  
4. **Exportación:** se genera un paquete HTML con todo embebido (listo para compartir).  
5. **Reset:** limpia la sesión para iniciar un nuevo proyecto.

## 4. Componentes clave

- **index.php:** interfaz y controlador principal del flujo.  
- **inc/upload.php:** maneja carga de archivos.  
- **inc/export.php:** genera el HTML final empaquetado.  
- **assets/js/app.js:** lógica de renderización y controles.  
- **templates/map_template.html:** base del visualizador exportado.

## 5. Estándares y convenciones

- Código PHP sin dependencias externas.  
- Bootstrap 5 para interfaz.  
- Leaflet 1.9.x como motor de mapa.  
- Identación con 4 espacios y comentarios normalizados.  
- Los paquetes exportados deben ser autosuficientes (sin rutas absolutas).

## 6. Plan de desarrollo

### Etapa actual
- Implementación del flujo base completo (carga, render, export).  
- Validación en entorno PHP portable y XAMPP.  

### Próximas etapas
- Soporte para múltiples capas simultáneas.  
- Incorporación de editor de estilo visual.  
- Módulo de plantillas temáticas.  
- Exportación directa en ZIP.  

---
**Autor:** Cristian Páez  
**Repositorio:** pc2web (GitHub)  
**Versión del documento:** 2025-10  

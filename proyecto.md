# Proyecto pc2web — Diseño y flujo de desarrollo

## 🎯 Propósito general

Construir una herramienta local y autosuficiente para generar visualizadores HTML a partir de datos espaciales exportados desde QGIS.

## 🏗️ Arquitectura general

**Tipo de aplicación:** Local, ejecutada sobre PHP portable.  
**Interfaz:** Web, con Bootstrap + Leaflet.  
**Backend:** PHP puro (sin frameworks).  
**Flujo:**  
1. Carga de GeoJSON + estilo + metadata.  
2. Render de previsualización con Leaflet.  
3. Posibilidad de agregar múltiples capas.  
4. Exportación del proyecto HTML.  
5. Reset del entorno.

## 🧠 Flujo funcional

1. **Inicio:**  
   Se abre `index.php` → muestra formulario inicial y mapa vacío.

2. **Carga de capa:**  
   El usuario selecciona un archivo GeoJSON, un JSON de estilo y un bloque de metadata.  
   PHP almacena temporalmente los datos y llama a `render.php`.

3. **Previsualización:**  
   La capa se carga sobre el mapa usando Leaflet y el estilo indicado.  
   Se genera una “tarjeta” (layer card) en la interfaz para controlar visibilidad y nombre.

4. **Agregar más capas:**  
   Repite el proceso. Cada capa se visualiza y se lista.

5. **Generar proyecto:**  
   PHP toma las capas cargadas y construye un paquete HTML completo dentro de `/export`.

6. **Reset:**  
   El botón “Reset” vacía los temporales y recarga la interfaz.

## 📦 Estructura modular del backend

| Archivo | Función |
|----------|----------|
| `upload.php` | Gestiona la subida de archivos (GeoJSON, estilos, metadata). |
| `render.php` | Genera los fragmentos HTML y JS para mostrar la capa en el mapa. |
| `export.php` | Construye la carpeta exportada con el HTML final y dependencias. |
| `reset.php` | Limpia la sesión y temporales. |

## 🎨 UI y diseño visual

- **Navbar superior** con botones:
  - “Cargar capa”
  - “Generar proyecto”
  - “Reset”
- **Panel lateral** (opcional) con lista de capas.
- **Mapa Leaflet** ocupando el área central.
- **Notificaciones** (Bootstrap Toasts o Alerts) para avisos.

## 🧪 Plan de pruebas inicial

1. Subida de GeoJSON → visible en el mapa.  
2. Subida de estilo → aplicado correctamente.  
3. Varias capas → visibles e independientes.  
4. Export → genera carpeta con `index.html` funcional.  
5. Reset → limpia correctamente la sesión.

## 🧭 Criterios de éxito

- Correr sin instalación.
- Exportar proyectos HTML autosuficientes.
- Interfaz estable, simple y clara.
- Mapa previsualizado igual que el final.

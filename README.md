# pc2web v1.0 — Plataforma Productor → Web

## Descripción general
**pc2web** es una herramienta local que permite a un **productor de mapas** generar un paquete web autosuficiente a partir de archivos **GeoJSON + SLD**, sin depender de servidores externos.  
Su objetivo es simplificar el flujo de publicación de mapas, permitiendo validar, configurar y exportar visualizaciones listas para distribución offline o hosting estático.

---

## Estructura del proyecto

```
pc2web/
├── index.php                ← Interfaz principal del modo productor
├── modules/
│   ├── navbar.php           ← Barra de navegación (modo productor y modo usuario)
│   └── footer.php           ← Pie de página común
├── js/
│   ├── map.js               ← Inicializa mapa Leaflet (base OSM + Argenmap)
│   └── producer.js          ← Control de flujo: carga, validación y exportación
├── css/
│   └── custom.css           ← Estilos generales Bootstrap 5.x + personalizados
├── data/
│   ├── input/
│   │   └── uploads/         ← Archivos subidos por el productor (.geojson + .sld)
│   └── cache/               ← Carpetas temporales validadas (tmp_YYYYMMDD_HHMMSS)
├── build/
│   └── export/              ← Salidas finales autosuficientes (modo usuario)
├── logs/
│   └── export_log.txt       ← Registro de exportaciones y errores
├── exporter.php             ← Empaqueta y genera /build/export/<proyecto>/
└── src/
    └── core/
        └── validation.php   ← Valida insumos y arma layers.json
```

---

## Flujo de trabajo

1. **Carga de archivos:**  
   El productor sube archivos `.geojson` y `.sld` a `/data/input/uploads/`.

2. **Validación:**  
   `validation.php` verifica estructura y correspondencia entre archivos, crea una carpeta temporal en `/data/cache/tmp_YYYYMMDD_HHMMSS/`, y genera `layers.json`.

3. **Visualización:**  
   `map.js` lee `layers.json` y renderiza las capas en Leaflet (modo productor).

4. **Configuración de popups:**  
   `producer.js` permite seleccionar campos visibles y etiquetas por capa, guardando `popup_config.json`.

5. **Exportación:**  
   `exporter.php` genera un paquete autosuficiente en `/build/export/<nombre_proyecto>/index.html` con:
   - Capas incrustadas inline (`layers.json`, `popup_config.json`)
   - Dependencias locales (Bootstrap, Leaflet)
   - Sin necesidad de servidor (sin CORS)

6. **Limpieza y log:**  
   Se vacían `/data/input` y `/data/cache/`, registrando la operación en `/logs/export_log.txt`.

---

## Requisitos del entorno

- **Servidor local:** XAMPP / WAMP / LAMP (PHP 8.x)
- **Librerías JS:**  
  - [Leaflet 1.9.x](https://leafletjs.com/)
  - [Bootstrap 5.x](https://getbootstrap.com/)
- **Navegador moderno:** Chrome, Firefox, Edge o Brave

---

## Estado actual
Estructura base limpia creada. Sin lógica implementada.  
Listo para iniciar desde **Issue 1 – Esqueleto base limpio** según el archivo `Issues.txt`.
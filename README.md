# pc2web

## 🧭 Descripción general

**pc2web** es una herramienta en PHP + JavaScript que transforma insumos
de QGIS (capas, estilos y metadatos) en proyectos web listos para
publicar. Está pensada para funcionar sin dependencias de base de datos
ni entornos complejos, siendo totalmente portable.

## ⚙️ Características principales

-   Interfaz dual: **Productor / Visor**
-   Soporte para carga y previsualización de capas GeoJSON.
-   Integración con **Leaflet** y **Bootstrap**.
-   Exportación automática a un paquete HTML autosuficiente.
-   Ejecución local simple: `php -S localhost:8000`.

## 🗂️ Estructura del repositorio

-   `index.php`: punto de entrada del sistema.
-   `config/`: parámetros globales y rutas.
-   `modules/`: componentes PHP reutilizables (navbar, mapa, paneles).
-   `assets/`: estilos, scripts e imágenes.
-   `data/`: almacenamiento de insumos y caché.
-   `build/export/`: resultados finales.
-   `docs/`: documentación técnica y roadmap.

## 🚀 Uso rápido

1.  Copiar el proyecto en una carpeta local.

2.  Ejecutar:

    ``` bash
    php -S localhost:8000
    ```

3.  Acceder desde el navegador a `http://localhost:8000`.

## 📘 Documentación

-   `pc2web.txt`: guía técnica interna.
-   `proyecto.md`: visión general del desarrollo y lineamientos
    estratégicos.

# Proyecto pc2web

## 🎯 Visión general

El proyecto **pc2web** busca convertir el flujo técnico de creación de
mapas en QGIS en un proceso web completamente automatizado. Su enfoque
es simplificar la publicación de capas geográficas sin depender de
servidores o configuraciones complejas.

El desarrollo se centra en una **arquitectura modular** en PHP +
JavaScript que pueda ejecutarse en cualquier entorno, desde una
instalación simple de PHP hasta XAMPP.

## 🌐 Abordaje del desarrollo

El sistema se construye bajo tres principios rectores:

1.  **Autonomía total:** cada exportación debe funcionar como un sitio
    HTML independiente.
2.  **Modularidad:** los componentes PHP y JS deben ser reutilizables
    entre productor y visor.
3.  **Escalabilidad:** el entorno debe permitir incorporar validaciones,
    logs y nuevas funciones sin romper compatibilidad.

## 🧩 Estructura definitiva del proyecto

pc2web/ ├── index.php ├── config/ ├── modules/ ├── assets/ ├── data/ ├──
build/export/ ├── logs/ ├── docs/ └── tests/

## 🔧 Componentes principales

  Módulo            Descripción
  ----------------- ----------------------------------------------
  `config/`         Configuración global (YAML + PHP).
  `modules/`        Bloques PHP (navbar, mapa, paneles, footer).
  `assets/`         Estilos, scripts e imágenes.
  `data/`           Archivos subidos por el productor.
  `build/export/`   Resultado final autosuficiente.

## 🧱 Flujo de trabajo

1.  **Carga de insumos:** El productor coloca los archivos en
    `/data/input/`.
2.  **Procesamiento:** PHP combina los datos con plantillas modulares.
3.  **Visualización:** Se muestra la previsualización en la IU.
4.  **Exportación:** El resultado se guarda en `/build/export/` con
    nombre de proyecto.

## 🧭 Roadmap general

  Fase   Objetivo                             Entregable
  ------ ------------------------------------ -------------------------------
  1      Estructura base + entorno PHP        `index.php` y módulos vacíos
  2      IU funcional (Leaflet + Bootstrap)   Interfaz productiva
  3      Motor de exportación                 Proyecto HTML autosuficiente
  4      Validaciones y logs                  Control de calidad
  5      Documentación final                  Versión lista para despliegue

## 🧰 Buenas prácticas

-   Mantener plantillas desacopladas del motor.
-   Controlar logs de procesos en `/logs/`.
-   Centralizar configuraciones en `config/settings.yaml`.
-   Documentar cambios en `docs/`.

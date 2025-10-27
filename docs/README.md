# pc2web

Generador de entregables web a partir de capas exportadas desde QGIS.

## 🚀 Objetivo
`pc2web` permite convertir insumos geoespaciales (GeoJSON, estilos y metadatos) en visualizadores HTML autosuficientes basados en Leaflet y Bootstrap.

## 🧱 Estructura general del proyecto

```
pc2web/
├── src/                 # Código fuente del generador
│   ├── core/            # Módulos principales del motor
│   ├── utils/           # Funciones de apoyo
│   ├── templates/       # Plantillas HTML base
│   ├── exporters/       # Transformadores QGIS → HTML
│   └── cli/             # Scripts de automatización
├── data/                # Insumos del usuario
│   ├── input/           # GeoJSON, estilos, metadata
│   └── cache/           # Archivos temporales
├── build/               # Salidas locales
│   └── export/          # Entregables finales
├── docs/                # Documentación
│   ├── proyecto.md
│   └── README.md
├── tests/               # Pruebas y validaciones
├── config/              # Configuración global
│   └── settings.yaml
├── logs/                # Registro de eventos
└── pc2web.txt           # Guía técnica interna
```

## 🧩 Tecnologías base

- Leaflet 1.9.x  
- Bootstrap 5.x  
- JavaScript (sin frameworks pesados)  
- YAML para configuración  
- Compatibilidad multiplataforma  

## 📦 Entregables

1. Visualizadores HTML autosuficientes.  
2. Carpeta `/export` con proyectos generados.  
3. Documentación de uso (`README.md`, `proyecto.md`).

## 🧭 Modo de uso

1. Colocar archivos GeoJSON y estilos en `/data/input/`
2. Ejecutar el script de generación en `/src/cli/`
3. Revisar resultados en `/build/export/`
4. Validar con los tests disponibles.

## 🧰 Mantenimiento

Las guías técnicas y decisiones de arquitectura están documentadas en `pc2web.txt`.

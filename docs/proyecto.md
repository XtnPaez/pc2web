# Proyecto pc2web

## 🎯 Propósito
Desarrollar una herramienta modular que transforme capas QGIS en entregables web listos para publicar, sin depender de servidores PHP ni entornos XAMPP.

## 🔧 Componentes principales

| Módulo | Descripción |
|--------|--------------|
| `core/` | Núcleo de procesamiento y generación HTML |
| `templates/` | Plantillas base Bootstrap + Leaflet |
| `exporters/` | Lógica para empaquetar y exportar proyectos |
| `utils/` | Funciones auxiliares (logs, paths, validaciones) |
| `cli/` | Scripts ejecutables para automatizar flujos |
| `data/` | Entrada y cache de usuario |
| `build/export/` | Resultado final del proceso |

## 🧱 Flujo de trabajo

1. **Carga de insumos:** El usuario coloca archivos en `/data/input/`  
2. **Procesamiento:** `core/` analiza los datos y los combina con plantillas  
3. **Generación:** `exporters/` crea la estructura HTML autosuficiente  
4. **Entrega:** El resultado se guarda en `/build/export/` listo para uso web  

## 📘 Documentación complementaria

- `README.md`: guía rápida de instalación y uso.  
- `pc2web.txt`: especificación técnica interna.  

## 🧭 Roadmap

| Fase | Objetivo | Entregable |
|------|-----------|-------------|
| 1 | Diseño de estructura y templates | Base funcional |
| 2 | Implementación del motor de exportación | Generador activo |
| 3 | Interfaz CLI | Automatización completa |
| 4 | Pruebas unitarias y QA | Validación final |
| 5 | Documentación y empaquetado | Versión lista para producción |

## 🧰 Buenas prácticas

- Mantener plantillas desacopladas del motor.  
- Controlar logs de procesos en `/logs/`.  
- Centralizar configuraciones en `config/settings.yaml`.  
- Documentar cualquier cambio en `docs/`.

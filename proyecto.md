# Proyecto: Visualizador Web de Mapas (GeoJSON → HTML)

## 1. Introducción

### 1.1 Objetivo
Desarrollar una aplicación de escritorio que permita generar entregables web basados en **GeoJSON y estilos exportados desde QGIS**, con interfaz local para configurar popups y documentación.  
El resultado será un paquete HTML autónomo, elegante y profesional.

### 1.2 Alcance
Incluye carga de archivos GeoJSON, configuración de popups, carga de metadata y generación de un visualizador final en HTML con Leaflet y Bootstrap.

---

## 2. Requisitos

### 2.1 Funcionales

- Cargar uno o varios **GeoJSON exportados desde QGIS**.  
- No se requiere validación ni configuración de estilos dentro de la app.  
- Configurar **campos para popups** y etiquetas.  
- Cargar **metadata del proyecto** mediante formulario (nombre, descripción, fuente, licencia).  
- Ordenar capas y definir visibilidad inicial.  
- Exportar un paquete HTML autónomo.

### 2.2 No funcionales

- Interfaz simple, responsive y accesible (ARIA).  
- Librerías locales (sin CDN).  
- Sin límite de capas ni de features.  
- Exportación sin restricción de tiempo.  

---

## 3. Arquitectura y tecnologías recomendadas

### 3.1 Stack sugerido
**Stack 100% JavaScript (Electron o Tauri + Leaflet + Bootstrap)**  
Ventajas:
- Multi-plataforma.  
- Sin necesidad de servidor PHP o Node externo.  
- Distribuible como ejecutable (Windows/Linux/Mac).

### 3.2 Estructura de carpetas generadas

```
visualizador_mapa/
│
├── index.html
├── /assets/
│   ├── leaflet/
│   ├── bootstrap/
│   ├── css/
│   └── js/
├── /data/
│   ├── capas/
│   └── config.json
└── /docs/
    └── documentacion.html
```

---

## 4. Plan de desarrollo

### Etapa 1 - Prototipo UI
- Maqueta en HTML con Bootstrap y Leaflet.  
- Navbar, sidebar, mapa y footer.  
- Simulación de capas con JSON estático.

### Etapa 2 - Carga de archivos
- Input para cargar GeoJSON y archivo de estilo exportado desde QGIS.  
- Listado dinámico de capas.

### Etapa 3 - Configuración de popups y metadata
- Panel para seleccionar campos visibles en popup.  
- Formulario de metadata del proyecto.  
- **Previsualización completa** del resultado final (idéntica al HTML exportado).

### Etapa 4 - Generación del entregable
- Botón **Generar** → crea estructura completa de carpetas.  
- Copia librerías embebidas.  
- Inserta HTML, CSS, JS y datos configurados.

### Etapa 5 - Documentación
- Generación automática de documentación al cargar cada GeoJSON y configurar popups.  
- Se completa un formulario con metadata general y específica por capa.

---

## 5. Riesgos y mitigación

| Riesgo | Impacto | Mitigación |
|--------|----------|------------|
| Fallo al exportar | Medio | Validación de rutas y archivos. |
| Diferencias en navegadores | Bajo | Test en Chrome/Edge/Firefox. |
| Errores en metadata | Bajo | Validación en formulario. |

---

## 6. Usabilidad y diseño

- Diseño responsive con Bootstrap grid.  
- Navbar y footer fijos.  
- Colores neutros con acentos configurables.  
- Accesibilidad: tabindex, aria-labels, alto contraste.  
- **Botón RESET** para iniciar un nuevo proyecto desde cero y limpiar configuración anterior.

---

## 7. Próximos pasos

1. Implementar base en **JavaScript puro (Electron o Tauri)**.  
2. Crear estructura inicial del proyecto.  
3. Prototipo de interfaz con carga de GeoJSON.  
4. Implementar lógica de configuración de popups y metadata.  
5. Integrar vista previa final.  
6. Añadir botón **Generar** y **Reset**.  
7. Documentar API interna.

---

## 8. Plan de implementación automatizada

El botón **Generar** deberá:

1. Crear estructura completa de carpetas.  
2. Copiar librerías embebidas (Leaflet, Bootstrap, JS utilitarios).  
3. Generar los siguientes archivos con código comentado:
   - `index.html`: estructura del mapa.  
   - `js/main.js`: carga de capas, inicialización Leaflet, eventos.  
   - `js/config.js`: configuración exportada desde la app.  
   - `css/styles.css`: estilos personalizados.  
   - `docs/documentacion.html`: documentación generada.  
4. Comprimir todo en un `.zip` descargable.  
5. Mostrar mensaje de éxito y ruta de guardado.

---

## 9. Conclusión

El proyecto generará entregables profesionales y autónomos, basados en insumos preprocesados desde QGIS.  
La app se enfocará en la configuración de popups, documentación y empaquetado final de forma simple y eficiente.

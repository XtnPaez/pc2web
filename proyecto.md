# Proyecto: Visualizador Web de Mapas (GeoJSON → HTML)

## 1. Introducción

### 1.1 Objetivo
Desarrollar una aplicación de escritorio para generar entregables web basados en GeoJSON, con interfaz de configuración local y resultado final en HTML autónomo.

### 1.2 Alcance
Incluye carga de archivos GeoJSON, configuración de estilo, popups, orden de capas y generación de mapa final con interfaz Bootstrap y Leaflet.

---

## 2. Requisitos

### 2.1 Funcionales
- Cargar uno o varios GeoJSON.
- Validar formato y contenido.
- Configurar estilos (color, opacidad, simbología).
- Seleccionar campos para popup.
- Ordenar capas y definir visibilidad inicial.
- Exportar un paquete HTML autónomo.

### 2.2 No funcionales
- Interfaz simple, responsive, y accesible (ARIA).
- Librerías locales (sin CDN).
- Mapa fluido hasta 10 capas de 10k features cada una.
- Exportación instantánea (<5 seg por proyecto promedio).

---

## 3. Arquitectura y tecnologías recomendadas

### 3.1 Opción preferida
**Stack 100% JavaScript (Electron o Tauri + Leaflet + Bootstrap)**  
Ventajas:
- Multi-plataforma.
- Sin necesidad de servidor PHP o Node externo.
- Distribuible como ejecutable (Windows/Linux/Mac).

### 3.2 Alternativa
**PHP + JS (localhost embebido)**, útil si el entorno ya usa Apache o XAMPP.

### 3.3 Estructura de carpetas generadas

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

1. **Etapa 1 - Prototipo UI**
   - Maqueta en HTML estático con Bootstrap y Leaflet.
   - Navbar, sidebar, mapa, footer.
   - Simulación de capas con JSON estático.

2. **Etapa 2 - Carga y validación de GeoJSON**
   - Input de archivo (drag & drop o selector).
   - Validación (estructura GeoJSON, CRS, geometrías).
   - Listado dinámico de capas.

3. **Etapa 3 - Configuración de estilos y popups**
   - Panel de propiedades (color picker, sliders, selectores de campo).
   - Previsualización instantánea en mini-mapa.

4. **Etapa 4 - Generación del entregable**
   - Botón **Generar** → crea estructura de carpetas.
   - Copia librerías embebidas.
   - Inserta HTML, CSS, JS y datos configurados.

5. **Etapa 5 - Documentación y empaquetado**
   - Página de documentación con metadata.
   - Generación de ZIP descargable.

---

## 5. Riesgos y mitigación

| Riesgo | Impacto | Mitigación |
|--------|----------|------------|
| Archivos GeoJSON muy grandes | Alto | Validar tamaño; aviso y reducción. |
| Inconsistencia CRS | Medio | Reproyectar o avisar. |
| Fallo al exportar | Medio | Logs y validación previa. |
| Diferencias en navegadores | Bajo | Test en Chrome/Edge/Firefox. |

---

## 6. Usabilidad y diseño

- Diseño responsive y limpio (Bootstrap grid).  
- Navbar y footer fijos.  
- Colores neutros con acentos configurables.  
- Accesibilidad: tabindex, aria-labels, alto contraste.

---

## 7. Próximos pasos

1. Confirmar stack final (Electron o PHP).
2. Crear estructura base de proyecto (plantilla vacía).
3. Implementar prototipo UI.
4. Implementar carga GeoJSON y validaciones.
5. Integrar configuradores y exportación.
6. Documentar API interna y generar ejemplo completo.

---

## 8. Plan de implementación automatizada

El botón **Generar** deberá:

1. Crear estructura completa de carpetas.
2. Copiar librerías embebidas (Leaflet, Bootstrap, JS utilitarios).
3. Generar los siguientes archivos con código y comentarios:
   - `index.html`: estructura del mapa.
   - `js/main.js`: carga de capas, inicialización Leaflet, eventos.
   - `js/config.js`: configuración exportada desde la app.
   - `css/styles.css`: estilos personalizados.
   - `docs/documentacion.html`: documentación generada.
4. Comprimir todo en un `.zip` descargable.
5. Mostrar mensaje de éxito y ruta de guardado.

---

## 9. Conclusión

El proyecto generará entregables profesionales, reproducibles y autosuficientes.  
La app facilitará la publicación rápida de visualizaciones interactivas, con estándares modernos y sin dependencias externas.

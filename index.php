<?php
/**
 * ============================================================
 * pc2webmap - index.php
 * ------------------------------------------------------------
 * Interfaz principal del modo PRODUCTOR.
 *
 * Estructura:
 *   - Incluye navbar y footer desde /modules/
 *   - Muestra panel lateral (capas) + mapa (Leaflet)
 *   - Se adapta al layout definido en /css/custom.css
 *
 * En modo "productor" se muestran herramientas de edición.
 * En modo "usuario" (exportado) se ocultan automáticamente.
 *
 * Dependencias:
 *   - Bootstrap 5.x
 *   - Leaflet 1.9.x
 *   - /js/map.js
 *   - /js/producer.js
 *   - /css/custom.css
 * ============================================================
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>pc2webmap</title>

  <!-- ==========================
       Librerías externas
  =========================== -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://unpkg.com/leaflet/dist/leaflet.css" rel="stylesheet" />

  <!-- ==========================
       Estilos propios
  =========================== -->
  <link href="css/custom.css" rel="stylesheet" />
</head>

<!-- 
  data-mode="producer" indica modo de trabajo.
  Al exportar, este atributo se reemplaza por data-mode="user".
-->
<body data-mode="producer">

  <!-- =========================================================
       NAVBAR (modo productor)
       ========================================================= -->
  <?php include 'modules/navbar.php'; ?>

  <!-- =========================================================
       CONTENIDO PRINCIPAL
       ========================================================= -->
  <main class="flex-fill">
    <div class="d-flex flex-row h-100">
      
      <!-- =====================================================
           PANEL LATERAL IZQUIERDO
           ===================================================== -->
      <div id="sidebar"
           class="border-end bg-light p-2"
           style="width:300px; overflow-y:auto;">
        <div id="layer-list" class="mt-2">
          <!-- Las capas validadas se cargarán aquí dinámicamente -->
        </div>
      </div>

      <!-- =====================================================
           CONTENEDOR PRINCIPAL DEL MAPA
           ===================================================== -->
      <div class="flex-grow-1 position-relative">
        <div id="map"></div>
      </div>

    </div>
  </main>

  <!-- =========================================================
       FOOTER (modo productor)
       ========================================================= -->
  <?php include 'modules/footer.php'; ?>

  <!-- =========================================================
       LIBRERÍAS JS
       ========================================================= -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- =========================================================
       SCRIPTS DEL PROYECTO
       ========================================================= -->
  <script src="js/map.js"></script>       <!-- Inicialización del mapa -->
  <script src="js/producer.js"></script>  <!-- Control de modo productor / validación / exportación -->

  <!-- =========================================================
       FIX: Ajustar mapa tras renderizado
       ========================================================= -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      setTimeout(() => {
        if (window.map && window.map.invalidateSize) {
          window.map.invalidateSize();
          console.log("🧭 Leaflet: tamaño del mapa ajustado correctamente.");
        }
      }, 500);
    });
  </script>

</body>
</html>

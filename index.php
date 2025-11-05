<?php
// index.php
// Interfaz principal modo PRODUCTOR para pc2web
// Estructura: Navbar fijo, footer fijo, layout full-height con panel izquierdo (capas) y panel derecho (mapa)
// Dependencias en desarrollo: Bootstrap 5 y Leaflet 1.9 vía CDN
// Nota: En exportación futura se copiarán/incrustarán para funcionar offline.

?><!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>pc2web — Productor</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5.x CSS (dev) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Leaflet 1.9.x CSS (dev) -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

  <!-- Estilos del proyecto -->
  <link rel="stylesheet" href="css/custom.css">
</head>
<body class="d-flex flex-column">

  <!-- NAVBAR -->
  <?php include __DIR__ . '/modules/navbar.php'; ?>

  <!-- CONTENIDO PRINCIPAL: layout de dos paneles -->
  <main id="app-main" class="container-fluid flex-grow-1">
    <div class="row g-0 h-100">
      <!-- Panel izquierdo: listado de capas y leyendas -->
      <aside id="left-panel" class="col-12 col-md-4 col-lg-3 border-end">
        <div class="p-3 h-100 d-flex flex-column">
          <h6 class="mb-3">Capas disponibles</h6>

          <!-- Contenedor dinámico de capas subidas y validadas -->
          <!-- Más adelante se poblará desde validation.php (layers.json) -->
          <div id="layerList" class="list-group list-group-flush small flex-grow-1 overflow-auto">
            <!-- Ejemplos de marcador de posición. Se eliminarán cuando integremos el validador -->
            <!--
            <label class="list-group-item">
              <input class="form-check-input me-1 layer-toggle" type="checkbox" data-layer-id="ejemplo_deptos">
              Departamentos (ejemplo)
            </label>
            -->
          </div>

          <!-- Leyendas asociadas a capas -->
          <div id="legendContainer" class="mt-3 small">
            <!-- Las leyendas de cada capa se inyectarán aquí cuando el checkbox esté activo -->
          </div>
        </div>
      </aside>

      <!-- Panel derecho: mapa -->
      <section id="map-panel" class="col-12 col-md-8 col-lg-9">
        <div id="map" class="w-100 h-100"></div>
      </section>
    </div>
  </main>

  <!-- FOOTER -->
  <?php include __DIR__ . '/modules/footer.php'; ?>

  <!-- Bootstrap 5.x JS (dev) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Leaflet 1.9.x JS (dev) -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <!-- Scripts del proyecto -->
  <script src="js/map.js"></script>
  <script src="js/producer.js"></script>
</body>
</html>

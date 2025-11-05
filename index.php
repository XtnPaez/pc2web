<?php
// ============================================================
// index.php
// ------------------------------------------------------------
// Interfaz principal del productor pc2web
// Usa Bootstrap 5 y Leaflet 1.9.x
// ============================================================
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>pc2web - Productor</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="css/custom.css">
</head>
<body>

   <!-- Navbar -->
  <?php include_once('modules/navbar.php'); ?>

  <!-- ============ MODAL A: Nombre del proyecto / Footer ============ -->
  <div class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="projectModalLabel">Configuración del proyecto</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <!-- Nombre del proyecto -->
          <div class="mb-3">
            <label for="nombreProyecto" class="form-label">Nombre del proyecto</label>
            <input type="text" id="nombreProyecto" class="form-control" maxlength="24" placeholder="Hasta 24 caracteres">
            <div class="form-text"><span id="npCount">0</span>/24</div>
          </div>
          <!-- Footer personalizado -->
          <div class="mb-3">
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" id="footerCustom">
              <label class="form-check-label" for="footerCustom">Personalizar footer</label>
            </div>
            <input type="text" id="footerTexto" class="form-control" maxlength="48" placeholder="Hasta 48 caracteres" disabled>
            <div class="form-text"><span id="ftCount">0</span>/48</div>
          </div>
          <small class="text-muted">Estos textos se aplicarán al exportar.</small>
        </div>
        <div class="modal-footer">
          <button id="saveProjectInfo" type="button" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- ============ MODAL B: Subir capa ============ -->
  <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="uploadForm" class="modal-content" enctype="multipart/form-data">
        <div class="modal-header">
          <h6 class="modal-title" id="uploadModalLabel">Subir capa</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <!-- Nombre y descripción -->
          <div class="mb-3">
            <label class="form-label" for="nombreCapa">Nombre de la capa</label>
            <input type="text" id="nombreCapa" name="nombre_capa" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="descripcionCapa">Descripción de la capa</label>
            <textarea id="descripcionCapa" name="descripcion_capa" class="form-control" rows="3" required></textarea>
          </div>
          <!-- Archivos -->
          <div class="mb-3">
            <label class="form-label" for="fileGeo">Archivo GeoJSON (.geojson)</label>
            <input type="file" id="fileGeo" name="geojson" accept=".geojson,application/geo+json,application/json" class="form-control" required>
            <div class="form-text">Máximo 100 MB</div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="fileSld">Archivo SLD (.sld)</label>
            <input type="file" id="fileSld" name="sld" accept=".sld,application/xml,text/xml" class="form-control" required>
            <div class="form-text">Máximo 100 MB. Debe compartir el mismo nombre base que el GeoJSON.</div>
          </div>
          <div class="alert alert-secondary py-2" id="uploadHint" style="display:none;"></div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Subir</button>
        </div>
      </form>
    </div>
  </div>


  <!-- Contenedor principal (reemplaza el anterior) -->
<div class="main-container">
  <!-- Sidebar -->
  <div id="sidebar" class="bg-light border-end p-3" style="width: 260px;">
    <h6 class="fw-bold mb-3">Capas cargadas</h6>
    <div id="layerList" class="form-check">
      <!-- Capas dinámicas -->
    </div>
  </div>

  <!-- Mapa -->
  <div id="mapContainer">
    <div id="map"></div>
  </div>
</div>

  <!-- Footer -->
  <?php include_once('modules/footer.php'); ?>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="js/map.js"></script>
    <script src="js/producer.js"></script>

</body>
</html>

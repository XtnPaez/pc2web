<?php
// modules/navbar.php
// Barra superior: nombre del proyecto (izquierda) y botones de producción (derecha)
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <!-- Marca izquierda -->
    <a id="projectName" class="navbar-brand fw-semibold" href="#">pc2web</a>

    <!-- Botones derecha -->
    <div class="d-flex gap-2">
      <!-- Nuevo: configuración general del proyecto -->
      <button id="btn-project" class="btn btn-sm btn-outline-light" type="button" title="Nombre del proyecto">
        Nombre del proyecto
      </button>

      <!-- Subida de capas -->
      <button id="btn-upload" class="btn btn-sm btn-outline-light" type="button" title="Subir capa">
        Subir capa
      </button>

      <!-- Validación, popups y exportación (sin cambios) -->
      <button id="btn-validate" class="btn btn-sm btn-outline-light" type="button" title="Validar capa">Validar capa</button>
      <button id="btn-popup" class="btn btn-sm btn-outline-light" type="button" title="Setear popup">Setear popup</button>
      <button id="btn-export" class="btn btn-sm btn-primary" type="button" title="Exportar">Exportar</button>
    </div>
  </div>
</nav>

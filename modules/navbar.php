<?php
// ============================================================
// pc2webmap - modules/navbar.php
// ------------------------------------------------------------
// Barra de navegación superior del modo productor.
// - Compatible con Bootstrap 5 (responsive).
// - Incluye botones funcionales conectados con producer.js.
// - El bloque #producer-tools se elimina automáticamente
//   en la exportación a modo usuario (por exporter.php).
// ============================================================
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">

    <!-- ======================================================
         Marca / título del proyecto
         ====================================================== -->
    <a class="navbar-brand fw-bold" href="#">pc2webmap</a>

    <!-- ======================================================
         Botón responsive (collapse en pantallas pequeñas)
         ====================================================== -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- ======================================================
         Contenedor colapsable del menú
         ------------------------------------------------------
         El bloque con id="producer-tools" se usa para agrupar
         los botones que se ocultarán al exportar a modo usuario.
         ====================================================== -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
      <ul id="producer-tools" class="navbar-nav mb-2 mb-lg-0 d-flex flex-row align-items-center gap-2">

        <!-- Botón: Cargar capas -->
        <li class="nav-item">
          <!-- ID esperado por producer.js: btn-upload -->
          <button id="btn-upload" class="btn btn-outline-light btn-sm">
            Cargar capa
          </button>
        </li>

        <!-- Botón: Cargar estilo SLD -->
        <li class="nav-item">
          <!-- ID esperado por producer.js: btn-style -->
          <button id="btn-style" class="btn btn-outline-light btn-sm">
            Cargar estilo
          </button>
        </li>

        <!-- Botón: Cargar metadata -->
        <li class="nav-item">
          <!-- ID esperado por producer.js: btn-metadata -->
          <button id="btn-metadata" class="btn btn-outline-light btn-sm">
            Metadatos
          </button>
        </li>

        <!-- Botón: Validar insumos -->
        <li class="nav-item">
          <!-- ID esperado por producer.js: btn-validate -->
          <button id="btn-validate" class="btn btn-outline-warning btn-sm">
            Validar
          </button>
        </li>

        <!-- Botón: Exportar proyecto -->
        <li class="nav-item">
          <!-- ID esperado por producer.js: btn-export -->
          <button id="btn-export" class="btn btn-success btn-sm">
            Exportar
          </button>
        </li>

      </ul>
    </div>
  </div>
</nav>

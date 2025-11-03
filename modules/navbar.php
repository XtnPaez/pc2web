<?php
// =============================================
// pc2webmap - modules/navbar.php
// ---------------------------------------------
// Barra superior fija del modo PRODUCTOR.
// Incluye botones de carga, validación,
// configuración y exportación.
// =============================================
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">

    <!-- Marca / título del proyecto -->
    <a class="navbar-brand fw-bold" href="#">pc2webmap</a>

    <!-- Botón responsive (pantallas pequeñas) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Contenido de la barra -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
      <ul class="navbar-nav mb-2 mb-lg-0">

        <!-- Botón CARGAR CAPA (futura funcionalidad) -->
        <li class="nav-item mx-1">
          <button id="btnUploadLayer" class="btn btn-outline-light btn-sm" disabled>
            Cargar capa
          </button>
        </li>

        <!-- Botón VALIDAR -->
        <li class="nav-item mx-1">
          <button id="btnValidate" class="btn btn-outline-light btn-sm">
            Validar
          </button>
        </li>

        <!-- Botón CONFIGURAR POPUPS (futuro) -->
        <li class="nav-item mx-1">
          <button id="btnPopupConfig" class="btn btn-outline-light btn-sm" disabled>
            Configurar popups
          </button>
        </li>

        <!-- Botón EXPORTAR -->
        <li class="nav-item mx-1">
          <button id="btnExport" class="btn btn-success btn-sm">
            Exportar
          </button>
        </li>

      </ul>
    </div>

  </div>
</nav>

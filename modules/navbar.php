<?php
// ============================================================
// modules/navbar.php
// ------------------------------------------------------------
// Navbar modo productor
// Issue 5: habilita botón “Setear popup” para abrir el modal
// ============================================================
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand fw-bold">pc2web</span>
    <div class="ms-auto d-flex gap-2">
      <!-- Modal A -->
      <button id="btnProjectInfo" class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#projectModal">
        Nombre del proyecto
      </button>
      <!-- Modal B -->
      <button id="btnUploadLayer" class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
        Subir capa
      </button>
      <!-- Validar -->
      <button id="btnValidate" class="btn btn-outline-warning btn-sm">
        Validar capa
      </button>
      <!-- Nuevo: Setear popup -->
      <button id="btnPopup" class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#popupConfigModal">
        Setear popup
      </button>
      <!-- Exportar -->
      <button id="btnExport" class="btn btn-success btn-sm" disabled>
        Exportar
      </button>
    </div>
  </div>
</nav>

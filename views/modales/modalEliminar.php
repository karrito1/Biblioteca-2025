<?php
$id = $_GET['id'] ?? 0;
?>

<div class="modal fade" id="modalEliminarUsuario" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-danger shadow-lg">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalEliminarLabel">Confirmar eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <p class="fs-5">¿Seguro que deseas eliminar este usuario?</p>
        <p class="text-muted">Esta acción <strong>no se puede deshacer</strong>.</p>
        <input type="hidden" id="idEliminar" value="<?= htmlspecialchars($id) ?>">
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger px-4" id="btnConfirmarEliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>
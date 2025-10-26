<?php
$id = $_GET['id'] ?? 0;
?>

<div class="modal fade" id="modalEliminarUsuario" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-danger shadow-lg">
      <div class="modal-header bg-danger text-white">
<<<<<<< HEAD
        <h5 class="modal-title" id="modalEliminarLabel">confirmar eliminacion</h5>
=======
        <h5 class="modal-title" id="modalEliminarLabel">Confirmar eliminación</h5>
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <p class="fs-5">¿Seguro que deseas eliminar este usuario?</p>
<<<<<<< HEAD
        <p class="text-muted">esta accion <strong>no se puede deshacer</strong>.</p>
=======
        <p class="text-muted">Esta acción <strong>no se puede deshacer</strong>.</p>
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
        <input type="hidden" id="idEliminar" value="<?= htmlspecialchars($id) ?>">
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger px-4" id="btnConfirmarEliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>
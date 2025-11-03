<?php
require_once(__DIR__ . "/../../models/MySQL.php");

$baseDatos = new MySQL();
$baseDatos->conectar();

$idLibro = isset($_GET['id']) ? intval($_GET['id']) : 0;
$fila = null;

if ($idLibro > 0) {
    $query = "SELECT * FROM libros WHERE id = $idLibro";
    $resultado = $baseDatos->efectuarConsulta($query);
    $fila = mysqli_fetch_assoc($resultado);
}

$baseDatos->desconectar();
?>

<div class="modal fade" id="modalEliminarLibro" tabindex="-1" aria-labelledby="modalEliminarLibroLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalEliminarLibroLabel"><i class="zmdi zmdi-delete"></i> Confirmar Eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar el libro <strong><?= htmlspecialchars($fila['titulo'] ?? '') ?></strong>?</p>
                <input type="hidden" id="eliminar_id" value="<?= htmlspecialchars($fila['id'] ?? '') ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="confirmarEliminarLibro" class="btn btn-danger">Eliminar</button>
            </div>
        </div>
    </div>
</div>
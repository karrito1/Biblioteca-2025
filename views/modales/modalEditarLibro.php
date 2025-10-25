<?php
require_once(__DIR__ . "/../../models/MySQL.php");

$baseDatos = new MySQL();
$baseDatos->conectar();

// Obtener ID del libro
$idLibro = isset($_GET['id']) ? intval($_GET['id']) : 0;
$fila = null;

if ($idLibro > 0) {
    $queryLibro = "SELECT * FROM libros WHERE id = $idLibro";
    $resultadoLibro = $baseDatos->efectuarConsulta($queryLibro);
    $fila = mysqli_fetch_assoc($resultadoLibro);
}

$baseDatos->desconectar();
?>

<div class="modal fade" id="modalEditarLibro" tabindex="-1" aria-labelledby="modalEditarLibroLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalEditarLibroLabel"><i class="zmdi zmdi-edit"></i> Editar Libro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <form id="formEditarLibro" action="/controllers/editarLibro.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Título:</label>
                        <input type="text" class="form-control" name="titulo" value="<?= htmlspecialchars($fila['titulo'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Autor:</label>
                        <input type="text" class="form-control" name="autor" value="<?= htmlspecialchars($fila['autor'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ISBN:</label>
                        <input type="text" class="form-control" name="isbn" value="<?= htmlspecialchars($fila['isbn'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Categoría:</label>
                        <input type="text" class="form-control" name="categoria" value="<?= htmlspecialchars($fila['categoria'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cantidad:</label>
                        <input type="number" class="form-control" name="cantidad" value="<?= htmlspecialchars($fila['cantidad'] ?? '') ?>" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Disponibilidad:</label>
                        <select name="disponibilidad" class="form-select" required>
                            <option value="Disponible" <?= ($fila['disponibilidad'] ?? '') === 'Disponible' ? 'selected' : '' ?>>Disponible</option>
                            <option value="No disponible" <?= ($fila['disponibilidad'] ?? '') === 'No disponible' ? 'selected' : '' ?>>No disponible</option>
                        </select>
                    </div>

                    <input type="hidden" name="id" value="<?= htmlspecialchars($fila['id'] ?? '') ?>">

                    <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
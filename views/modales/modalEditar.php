<?php
<<<<<<< HEAD
include_once "../models/MySQL.php";

$mysql = new MySQL();
$mysql->conectar();

$idempleados = $_GET['id'] ?? null;
$fila = null;

if ($idempleados) {
    // Traer datos del empleado
    $queryEmpleado = "SELECT * FROM usuarios WHERE id = $idempleados";
    $resultadoEmpleado = $mysql->efectuarConsulta($queryEmpleado);
    $fila = mysqli_fetch_assoc($resultadoEmpleado);
}


$mysql->desconectar();
?>



=======
require_once(__DIR__ . "/../../models/MySQL.php");

$baseDatos = new MySQL();
$baseDatos->conectar();

// Obtener y sanear el ID del usuario
$idempleados = isset($_GET['id']) ? intval($_GET['id']) : 0;
$fila = null;

if ($idempleados > 0) {
    $queryEmpleado = "SELECT * FROM usuarios WHERE id = $idempleados";
    $resultadoEmpleado = $baseDatos->efectuarConsulta($queryEmpleado);
    $fila = mysqli_fetch_assoc($resultadoEmpleado);
}
$baseDatos->desconectar();
?>

>>>>>>> origin/main
<div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-labelledby="modalEditarClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
<<<<<<< HEAD
                <h5 class="modal-title" id="modalEditarClienteLabel">Editar Cliente</h5>
=======
                <h5 class="modal-title" id="modalEditarClienteLabel">Editar Usuario</h5>
>>>>>>> origin/main
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
<<<<<<< HEAD
                <?php if ($fila): ?>
                    <form method="POST" id="formEditar" action="/Biblioteca-2025/controllers/editarLibros.php">
                        <input type="hidden" name="id" value="<?= $fila['id'] ?>">


                        <!-- DATOS PERSONALES -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre:</label>
                                <input type="text" class="form-control form-control-sm" id="nombre" name="nombre" value="<?= $fila['nombre'] ?>" required>
                            </div>

                            <div class=" col-md-6 mb-3">
                                <label for="email" class="form-label">Correo electrónico:</label>
                                <input type="email" class="form-control form-control-sm" id="email" name="email" value="<?= $fila['email'] ?>" required>
                            </div>
                        </div>

                        <!-- DATOS DE CUENTA -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="Roles" class="form-label">Rol:</label>
                                <select id="Roles" name="Roles" class="form-select form-select-sm" required>
                                    <option value="">Seleccione...</option>
                                    <option value="ADMINISTRADOR">Administrador</option>
                                    <option value="CLIENTE">Cliente</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="passwordd" class="form-label">Contraseña:</label>
                                <input type="password" class="form-control form-control-sm" id="passwordd" name="passwordd" required>
                            </div>
                        </div>

                        <!-- CONTACTO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono:</label>
                                <input type="text" class="form-control form-control-sm" id="telefono" name="telefono" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="direccion" class="form-label">Dirección:</label>
                                <input type="text" class="form-control form-control-sm" id="direccion" name="direccion" required>
                            </div>
                        </div>

                        <!-- OTROS DATOS -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado:</label>
                                <input type="text" class="form-control form-control-sm" id="estado" name="estado" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fecha_registro" class="form-label">Fecha de registro:</label>
                                <input type="date" class="form-control form-control-sm" id="fecha_registro" name="fecha_registro" required>
                            </div>
                        </div>

                        <!-- BOTÓN -->
                        <button type="submit" class="btn btn-success w-100" name="btn_editar" value="ok">
                            guardar
                        </button>
                    <?php else: ?>
                        <div class="alert alert-danger text-center">Empleado no encontrado</div>
                    <?php endif; ?>
                    </form>


=======
                <form id="formEditarUsuario">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre:</label>
                            <input type="text" class="form-control form-control-sm" name="nombre" value="<?= htmlspecialchars($fila['nombre'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo electrónico:</label>
                            <input type="email" class="form-control form-control-sm" name="email" value="<?= htmlspecialchars($fila['email'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Rol:</label>
                            <select name="Roles" class="form-select form-select-sm" required>
                                <option value="">Seleccione...</option>
                                <option value="ADMINISTRADOR" <?= ($fila['Roles'] ?? '') === 'ADMINISTRADOR' ? 'selected' : '' ?>>Administrador</option>
                                <option value="CLIENTE" <?= ($fila['Roles'] ?? '') === 'CLIENTE' ? 'selected' : '' ?>>Cliente</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contraseña:</label>
                            <input type="password" class="form-control form-control-sm" name="passwordd" placeholder="Dejar en blanco para no cambiar">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Teléfono:</label>
                            <input type="text" class="form-control form-control-sm" name="telefono" value="<?= htmlspecialchars($fila['telefono'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dirección:</label>
                            <input type="text" class="form-control form-control-sm" name="direccion" value="<?= htmlspecialchars($fila['direccion'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Estado:</label>
                            <input type="text" class="form-control form-control-sm" name="estado" value="<?= htmlspecialchars($fila['estado'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de registro:</label>
                            <input type="date" class="form-control form-control-sm" name="fecha_registro" value="<?= isset($fila['fecha_registro']) ? substr($fila['fecha_registro'], 0, 10) : '' ?>" required>
                        </div>
                    </div>

                    <input type="hidden" name="id" value="<?= htmlspecialchars($fila['id'] ?? '') ?>">

                    <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
                </form>
>>>>>>> origin/main
            </div>
        </div>
    </div>
</div>
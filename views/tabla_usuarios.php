<?php
require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

$query = "SELECT id, nombre, email, telefono, direccion, estado, fecha_registro, Roles FROM usuarios";
$result = $baseDatos->efectuarConsulta($query);
?>

<div class="card p-4 mb-5 shadow">
    <h3 class="mb-4"><i class="zmdi zmdi-accounts"></i> Usuarios Registrados</h3>

    <!--  Acciones rapidas -->
    <div class="mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarUsuario">
            <i class="zmdi zmdi-account-add"></i> registar usuario
        </button>
    </div>

    <!--  Tabla -->
    <div class="table-responsive ">
        <table id="tablausuarios" class="table table-striped table-bordered">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>telefono</th>
                    <th>direccion</th>
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['id']) ?></td>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= htmlspecialchars($fila['email']) ?></td>
                        <td><?= htmlspecialchars($fila['telefono']) ?></td>
                        <td><?= htmlspecialchars($fila['direccion']) ?></td>
                        <td><?= htmlspecialchars($fila['estado']) ?></td>
                        <td><?= htmlspecialchars($fila['fecha_registro']) ?></td>
                        <td><?= htmlspecialchars($fila['Roles']) ?></td>
                        <td class="text-center">
                            <button class="btn btn-primary btnEditar" data-id="<?= $fila['id'] ?>">
                                Editar
                            </button>
                            <button class="btn btn-danger btnEliminar" data-id="<?= $fila['id'] ?>">
                                Eliminar
                            </button>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php $baseDatos->desconectar(); ?>
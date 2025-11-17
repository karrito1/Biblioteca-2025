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
                    <th>Telefono</th>
                    <th>Direccion</th>
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
                        <td class="text-center">
                            <?php
                            $estado = htmlspecialchars($fila['estado']);
                            $claseBoton = '';

                            switch (strtolower($estado)) {
                                case 'pendiente':
                                    $claseBoton = 'btn-warning';
                                    break;
                                case 'aprobado':
                                case 'confirmado':
                                    $claseBoton = 'btn-success';
                                    break;
                                case 'cancelado':
                                case 'rechazado':
                                    $claseBoton = 'btn-danger';
                                    break;
                                default:
                                    $claseBoton = 'btn-secondary';
                            }
                            ?>
                            <button class="btn btn-sm <?= $claseBoton ?>" disabled>
                                <?= $estado ?>
                            </button>
                        </td>

                        <td><?= htmlspecialchars($fila['fecha_registro']) ?></td>
                        <td><?= htmlspecialchars($fila['Roles']) ?></td>
                        <td class="text-center">
                            <button class="btn btn-primary btnEditar" data-id="<?= $fila['id'] ?>">
                                <span class="material-symbols-outlined">manage_accounts</span>
                            </button>

                            <!-- Boton que abre el modal -->
                            <button class="btn btn-danger btnEliminar" data-id="<?= $fila['id'] ?>">
                                <span class="material-symbols-outlined">
                                    person_remove
                                </span>
                            </button>
                            <a href="../reports/pdf_usuarios.php" class="btn btn-danger" target="_blank">
                                <span class="material-symbols-outlined">
                                    picture_as_pdf
                                </span>
                            </a>

                            <a href="../reports/excel_usuarios.php" class="btn btn-success">
                                <i class="zmdi zmdi-file-excel"></i> Exportar Excel
                            </a>


                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php $baseDatos->desconectar(); ?>
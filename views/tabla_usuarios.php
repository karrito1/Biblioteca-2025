<?php
require_once("../models/MySQL.php");

$db = new MySQL();
$conexion = $db->conectar();

$query = "SELECT id, nombre, email, telefono, direccion, estado, fecha_registro, Roles 
          FROM usuarios WHERE roles='ADMINISTRADOR'";
$result = $db->efectuarConsulta($query);
?>

<div class="card p-4 mb-5 shadow">
    <h3><i class="zmdi zmdi-accounts"></i> Usuarios Registrados</h3>

    <div class="table-responsive">
        <table id="tablausuarios" class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
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
                            <button class="btn btn-sm btn-primary"><i class="zmdi zmdi-edit"></i></button>
                            <button class="btn btn-sm btn-danger"><i class="zmdi zmdi-delete"></i></button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php $db->desconectar(); ?>

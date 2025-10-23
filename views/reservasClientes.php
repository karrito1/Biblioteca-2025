<?php
session_start();
require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();


$usuario_id = $_SESSION['usuario_id'];
$query = "SELECT reservas.fecha_reserva,reservas.estado,usuarios.nombre,libros.titulo,libros.isbn
FROM reservas
LEFT JOIN usuarios ON reservas.usuario_id = usuarios.id
LEFT JOIN libros ON reservas.libro_id = libros.id
where usuarios.id=$usuario_id;";
$result = $baseDatos->efectuarConsulta($query);
?>

<div class="card p-4 mb-5 shadow">
    <h3 class="mb-4"><i class="zmdi zmdi-calendar"></i> Mis Reservas</h3>

    <div class="table-responsive">
        <table id="tablareservas" class="table table-striped table-bordered">
            <thead class="table-dark text-center">
                <tr>
                    <th>Nombre</th>
                    <th>Fecha de Reserva</th>
                    <th>Estado</th>
                    <th>Titulo</th>
                    <th>Isbn</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) { ?>
                    <?php while ($fila = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['fecha_reserva']) ?></td>
                            <td><?= htmlspecialchars($fila['estado']) ?></td>
                            <td><?= htmlspecialchars($fila['titulo']) ?></td>
                            <td><?= htmlspecialchars($fila['isbn']) ?></td>

                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No tienes reservas registradas.
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php $baseDatos->desconectar(); ?>
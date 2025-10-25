<?php
session_start();
require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['roles'])) {
    die("Error: sesión no válida.");
}

$usuario_id = $_SESSION['usuario_id'];
$rol = strtoupper($_SESSION['roles']); // Convertimos a maayusculas

// Consulta  segun el rol
if ($rol === "CLIENTE") {
    $query = "SELECT reservas.fecha_reserva,
                     reservas.estado,
                     usuarios.nombre,
                     libros.titulo,
                     libros.isbn
              FROM reservas
              LEFT JOIN usuarios ON reservas.usuario_id = usuarios.id
              LEFT JOIN libros ON reservas.libro_id = libros.id
              WHERE usuarios.id = $usuario_id;";
} elseif ($rol === "ADMINISTRADOR") {
    $query = "SELECT reservas.fecha_reserva,
                     reservas.estado,
                     usuarios.nombre,
                     libros.titulo,
                     libros.isbn
              FROM reservas
              LEFT JOIN usuarios ON reservas.usuario_id = usuarios.id
              LEFT JOIN libros ON reservas.libro_id = libros.id;";
} else {
    die("Error: rol no valido o no definido.");
}

//  Ejecutar la consulta
$result = $baseDatos->efectuarConsulta($query);

// Verificar si hubo error en la consulta
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conexion));
}
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
                    <th>Título</th>
                    <th>ISBN</th>
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
                        <td colspan="5" class="text-center text-muted">
                            No tienes reservas registradas.
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php $baseDatos->desconectar(); ?>
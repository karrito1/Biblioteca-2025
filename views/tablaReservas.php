<?php
session_start();
require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Validar sesion
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['roles'])) {
    die("Error: sesion no valida.");
}

$usuario_id = (int) $_SESSION['usuario_id'];
$rol = strtoupper(trim($_SESSION['roles']));

// Consulta segun el rol
if ($rol === "CLIENTE") {
    $query = " SELECT 
            reservas.id,
            reservas.fecha_reserva,
            reservas.estado,
            usuarios.nombre,
            libros.titulo,
            libros.isbn
        FROM reservas
        LEFT JOIN usuarios ON reservas.usuario_id = usuarios.id
        LEFT JOIN libros ON reservas.libro_id = libros.id
        WHERE reservas.usuario_id = $usuario_id
        ORDER BY reservas.fecha_reserva DESC;
    ";
} elseif ($rol === "ADMINISTRADOR") {
    $query = " SELECT 
            reservas.id,
            reservas.fecha_reserva,
            reservas.estado,
            usuarios.nombre,
            libros.titulo,
            libros.isbn
        FROM reservas
        LEFT JOIN usuarios ON reservas.usuario_id = usuarios.id
        LEFT JOIN libros ON reservas.libro_id = libros.id
        ORDER BY reservas.fecha_reserva DESC;
    ";
} else {
    die("Error: rol no valido o no definido.");
}

// Ejecutar la consulta
$result = $baseDatos->efectuarConsulta($query);
?>

<div class="card p-4 mb-5 shadow">
    <h3 class="mb-4"><i class="zmdi zmdi-calendar"></i>
        <?= $rol === "ADMINISTRADOR" ? "Todas las Reservas" : "Mis Reservas" ?>

    </h3>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formRegistrarReserva">
        <i class="zmdi zmdi-assignment-check"></i> Registrar Reserva
    </button>
    <div class="table-responsive">
        <table id="tablareservas" class="table table-striped table-bordered">
            <thead class="table-dark text-center">
                <tr>
                    <th>Nombre</th>
                    <th>Fecha de Reserva</th>
                    <th>Estado</th>
                    <th>Titulo</th>
                    <th>ISBN</th>
                    <?php if ($rol === "ADMINISTRADOR") { ?>
                        <th>Acciones</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) { ?>
                    <?php while ($fila = mysqli_fetch_assoc($result)) { ?>
                        <tr class="text-center">
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['fecha_reserva']) ?></td>
                            <td class="text-center">
                                <?php
                                $estado = htmlspecialchars($fila['estado']);
                                $claseBoton = '';

                                switch (strtolower($estado)) {
                                    case 'pendiente':
                                        $claseBoton = 'btn-warning'; // amarillo
                                        break;
                                    case 'aprobada':
                                        $claseBoton = 'btn-success'; // verde
                                        break;
                                    case 'rechazada':
                                        $claseBoton = 'btn-danger'; // rojo
                                        break;
                                    default:
                                        $claseBoton = 'btn-secondary'; // gris por defecto
                                }
                                ?>
                                <button class="btn btn-sm <?= $claseBoton ?>" disabled>
                                    <?= ucfirst($estado) ?>
                                </button>
                            </td>

                            <td><?= htmlspecialchars($fila['titulo']) ?></td>
                            <td><?= htmlspecialchars($fila['isbn']) ?></td>

                            <!-- SOLO mostrar para ADMIN -->
                            <?php if ($rol === "ADMINISTRADOR") { ?>
                                <td>
                                    <button class="btn btn-sm btn-primary btnEditar" data-id="<?= $fila['id'] ?>">
                                        <i class="zmdi zmdi-edit"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-danger btnEliminar" data-id="<?= $fila['id'] ?>">
                                        <i class="zmdi zmdi-delete"></i> Eliminar
                                    </button>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="<?= $rol === 'ADMINISTRADOR' ? 6 : 5 ?>" class="text-center text-muted">
                            <?= $rol === 'ADMINISTRADOR' ? 'No hay reservas registradas.' : 'No tienes reservas registradas.' ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php $baseDatos->desconectar(); ?>
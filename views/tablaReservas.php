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
    $query = "SELECT 
                reservas.id,
                reservas.usuario_id,
                reservas.libro_id,
                reservas.fecha_reserva,
                reservas.estado,
                usuarios.nombre,
                libros.titulo,
                libros.isbn
              FROM reservas
              LEFT JOIN usuarios ON reservas.usuario_id = usuarios.id
              LEFT JOIN libros ON reservas.libro_id = libros.id
              WHERE reservas.usuario_id = $usuario_id
              ORDER BY reservas.fecha_reserva DESC";
} elseif ($rol === "ADMINISTRADOR") {
    $query = "SELECT 
                reservas.id,
                reservas.usuario_id,
                reservas.libro_id,
                reservas.fecha_reserva,
                reservas.estado,
                usuarios.nombre,
                libros.titulo,
                libros.isbn
              FROM reservas
              LEFT JOIN usuarios ON reservas.usuario_id = usuarios.id
              LEFT JOIN libros ON reservas.libro_id = libros.id
              ORDER BY reservas.fecha_reserva DESC";
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

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarReserva">
        <span class="material-symbols-outlined">
            add_card
        </span>
    </button>

    <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered">
            <thead class="table-dark text-center">
                <tr>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Libro</th>
                    <th>ISBN</th>
                    <?php if ($rol === "ADMINISTRADOR") { ?><th>Acciones</th><?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) {
                    while ($fila = mysqli_fetch_assoc($result)) { ?>
                        <tr class="text-center">
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['fecha_reserva']) ?></td>
                            <td>
                                <?php
                                $estado = strtolower($fila['estado']);
                                $clase = $estado === 'pendiente' ? 'btn-warning' : ($estado === 'aprobada' ? 'btn-success' : ($estado === 'rechazada' ? 'btn-danger' : 'btn-secondary'));
                                ?>
                                <button class="btn btn-sm <?= $clase ?>" disabled><?= ucfirst($fila['estado']) ?></button>
                            </td>
                            <td data-id="<?= $fila['libro_id'] ?>"><?= htmlspecialchars($fila['titulo']) ?></td>
                            <td><?= htmlspecialchars($fila['isbn']) ?></td>
                            <?php if ($rol === "ADMINISTRADOR") { ?>
                                <td>
                                    <button class="btn btn-primary btn-accion editar-reserva-btn" data-id="<?= $fila['id'] ?>">
                                        <span class="material-symbols-outlined">edit_calendar</span>
                                    </button>

                                    <button class="btn btn-danger btn-accion eliminar-reserva-btn" data-id="<?= $fila['id'] ?>">
                                        <span class="material-symbols-outlined">free_cancellation</span>
                                    </button>

                                    <button class="btn btn-success btn-accion convertir-prestamo-btn" data-id="<?= $fila['id'] ?>">
                                        <i class="zmdi zmdi-book"></i>
                                    </button>
                                    <a href="../reports/pdf_reservas.php" class="btn btn-danger" target="_blank">
                                        <span class="material-symbols-outlined">
                                            picture_as_pdf
                                        </span>
                                    </a>

                                    <a href="../reports/excel_reservas.php" class="btn btn-success">
                                        <i class="bi bi-file-excel"></i> Exportar Excel
                                    </a>


                                </td>
                            <?php } ?>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="<?= $rol === "ADMINISTRADOR" ? 6 : 5 ?>" class="text-center text-muted">No hay reservas</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php $baseDatos->desconectar(); ?>
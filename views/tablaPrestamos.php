<?php
session_start();
require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

//  Validar que la sesión este activa
if (!isset($_SESSION['usuario_id'])) {
    echo "<div class='alert alert-warning text-center mt-3'> No hay sesión activa. Por favor, inicia sesion.</div>";
    exit;
}

$usuario_id = intval($_SESSION['usuario_id']); // Sanitizar ID del usuario
$rol = isset($_SESSION['roles']) ? strtoupper(trim($_SESSION['roles'])) : ""; // Normalizamos el rol

//  Consulta segn el rol del usuario
if ($rol === "ADMINISTRADOR") {
    // El administrador ve todos los prestamos
    $query = "SELECT prestamos.id,
                     prestamos.fecha_prestamo,
                     prestamos.fecha_devolucion,
                     prestamos.estado,
                     usuarios.nombre,
                     libros.isbn
              FROM prestamos
              LEFT JOIN usuarios ON prestamos.usuario_id = usuarios.id
              LEFT JOIN libros ON prestamos.libro_id = libros.id;";
} else {
    // Lclentes solo ven sus propios prstamos
    $query = "SELECT prestamos.id,
                     prestamos.fecha_prestamo,
                     prestamos.fecha_devolucion,
                     prestamos.estado,
                     usuarios.nombre,
                     libros.isbn
              FROM prestamos
              LEFT JOIN usuarios ON prestamos.usuario_id = usuarios.id
              LEFT JOIN libros ON prestamos.libro_id = libros.id
              WHERE usuarios.id = $usuario_id;";
}

//  Ejecutar la consulta
$result = $baseDatos->efectuarConsulta($query);


if (!$result) {
    die("<div class='alert alert-danger text-center mt-3'>
            Error al obtener los preestamos: " . htmlspecialchars(mysqli_error($conexion)) . "
        </div>");
}
?>

<div class="card p-4 mb-5 shadow">
    <h3 class="mb-4">
        <i class="zmdi zmdi-assignment"></i> Préstamos Registrados
    </h3>

    <?php if ($rol === "ADMINISTRADOR") { ?>
        <div class="mb-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarPrestamo">
                <i class="zmdi zmdi-assignment-check"></i> Registrar Préstamo
            </button>
        </div>
    <?php } ?>

    <div class="table-responsive">
        <table id="tablaPrestamos" class="table table-striped table-bordered">
            <thead class="table-dark text-center">
                <tr>
                    <th>Fecha Préstamo</th>
                    <th>Fecha Devolución</th>
                    <th>Estado</th>
                    <th>Nombre</th>
                    <th>ISBN</th>
                    <?php if ($rol === "ADMINISTRADOR") { ?>
                        <th>Acciones</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) { ?>
                    <?php while ($fila = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['fecha_prestamo']) ?></td>
                            <td><?= htmlspecialchars($fila['fecha_devolucion']) ?></td>
                            <td class="text-center">
                                <?php
                                $estado = htmlspecialchars($fila['estado']);
                                $claseBoton = '';

                                switch (strtolower($estado)) {
                                    case 'activo':
                                        $claseBoton = 'btn-success'; // verde
                                        break;
                                    case 'devuelto':
                                        $claseBoton = 'btn-secondary'; // gris
                                        break;
                                    case 'retrasado':
                                        $claseBoton = 'btn-danger'; // rojo
                                        break;
                                    default:
                                        $claseBoton = 'btn-warning'; // amarillo por defecto
                                }
                                ?>
                                <button class="btn btn-sm <?= $claseBoton ?>" disabled>
                                    <?= ucfirst($estado) ?>
                                </button>
                            </td>

                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['isbn']) ?></td>

                            <?php if ($rol === "ADMINISTRADOR") { ?>
                                <td class="text-center">
                                    <button class="btn btn-primary btnEditar" data-id="<?= $fila['id'] ?>">
                                        <i class="zmdi zmdi-edit"></i> Editar
                                    </button>
                                    <button class="btn btn-danger btnEliminar" data-id="<?= $fila['id'] ?>">
                                        <i class="zmdi zmdi-delete"></i> Eliminar
                                    </button>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="<?= $rol === "ADMINISTRADOR" ? 6 : 5 ?>" class="text-center text-muted">
                            No hay prestamos registrados.
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
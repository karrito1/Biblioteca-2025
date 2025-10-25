<?php
session_start(); // 👈 Asegúrate de tener la sesión iniciada

require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Obtener rol del usuario
$rol = isset($_SESSION['roles']) ? strtoupper(trim($_SESSION['roles'])) : "";

// Consulta para obtener los libros
$query = "SELECT id, titulo, autor, isbn, categoria, cantidad, disponibilidad, fecha_registro FROM libros";
$result = $baseDatos->efectuarConsulta($query);
?>

<div class="card p-4 mb-5 shadow">
    <h3 class="mb-4"><i class="zmdi zmdi-book"></i> Libros Registrados</h3>

    <?php if ($rol === "ADMINISTRADOR") { ?>
        <div class="mb-4">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="">
                <i class="zmdi zmdi-plus"></i> Nuevo libro
            </button>
        </div>

    <?php } elseif ($rol === "CLIENTE") { ?>
        <div class="mb-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="">
                <i class="zmdi zmdi-assignment"></i> Registrar préstamo
            </button>
        </div>
    <?php } ?>

    <!-- Tabla -->
    <div class="table-responsive">
        <table id="tablalibros" class="table table-striped table-bordered">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>ISBN</th>
                    <th>Categoría</th>
                    <th>Cantidad</th>
                    <th>Disponibilidad</th>
                    <th>Fecha Registro</th>
                    <?php if ($rol === "ADMINISTRADOR") { ?>
                        <th>Acciones</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['id']) ?></td>
                        <td><?= htmlspecialchars($fila['titulo']) ?></td>
                        <td><?= htmlspecialchars($fila['autor']) ?></td>
                        <td><?= htmlspecialchars($fila['isbn']) ?></td>
                        <td><?= htmlspecialchars($fila['categoria']) ?></td>
                        <td><?= htmlspecialchars($fila['cantidad']) ?></td>
                        <td><?= htmlspecialchars($fila['disponibilidad']) ?></td>
                        <td><?= htmlspecialchars($fila['fecha_registro']) ?></td>

                        <?php if ($rol === "ADMINISTRADOR") { ?>
                            <td class="text-center">
                                <button class="btn btn-primary btnEditarLibro" data-id="<?= $fila['id'] ?>">
                                    <i class="zmdi zmdi-edit"></i>
                                </button>
                                <button class="btn btn-danger btnEliminarLibro" data-id="<?= $fila['id'] ?>">
                                    <i class="zmdi zmdi-delete"></i>
                                </button>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php $baseDatos->desconectar(); ?>
<?php
session_start();

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
        <div class="action-buttons">
            <button class="btn btn-primary btn-accion" data-bs-toggle="modal" data-bs-target="#modalRegistrarLibro">
                <span class="material-symbols-outlined">
                    bookmark_add
                </span>
            </button>
            <a href="../reports/pdf_libros.php" class="btn btn-danger" target="_blank">
                <span class="material-symbols-outlined">
                    picture_as_pdf
                </span>
            </a>

            <a href="../reports/excel_libros.php" class="btn btn-success">
                <i class="zmdi zmdi-file-text"></i> Excel
            </a>


        </div>

    <?php } elseif ($rol === "CLIENTE") { ?>
        <div class="mb-4">
            <i class="zmdi zmdi-assignment"></i> Libros de la biblioteca
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
                                    <span class="material-symbols-outlined">
                                        bookmark_added
                                    </span>
                                </button>

                                <button class="btn btn-danger btnEliminarLibro" data-id="<?= $fila['id'] ?>">
                                    <span class="material-symbols-outlined">
                                        bookmark_remove
                                    </span>
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
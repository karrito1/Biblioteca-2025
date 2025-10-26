<?php
require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Consulta para obtener los libros
$query = "SELECT id, titulo, autor, isbn, categoria, cantidad, disponibilidad, fecha_registro FROM libros";
$result = $baseDatos->efectuarConsulta($query);
?>

<div class="card p-4 mb-5 shadow">
    <h3 class="mb-4"><i class="zmdi zmdi-book"></i> Libros Registrados</h3>

    <div class="mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarLibro">
            <i class="zmdi zmdi-book"></i> Nuevo Libro
        </button>
    </div>


    <!--  Tabla -->
    <div class="table-responsive">
        <table id="tablalibros" class="table table-striped table-bordered">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
<<<<<<< HEAD
                    <th>titulo</th>
                    <th>Autor</th>
                    <th>ISBN</th>
                    <th>categoria</th>
=======
                    <th>Título</th>
                    <th>Autor</th>
                    <th>ISBN</th>
                    <th>Categoría</th>
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
                    <th>Cantidad</th>
                    <th>Disponibilidad</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
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
                        <td class="text-center">
                            <button class="btn btn-primary btnEditarLibro" data-id="<?= $fila['id'] ?>">
                                Editar
                            </button>
                            <button class="btn btn-danger btnEliminarLibro" data-id="<?= $fila['id'] ?>">
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
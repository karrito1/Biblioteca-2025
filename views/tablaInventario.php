<?php
require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

$query = "SELECT 
 l.id AS libro_id,
    l.titulo,
    l.autor,
    l.categoria,
    l.cantidad AS cantidad_total,
    (SELECT COUNT(*) FROM prestamos p WHERE p.libro_id = l.id AND p.estado = 'activo') AS prestados,
    (SELECT COUNT(*) FROM reservas r WHERE r.libro_id = l.id AND (r.estado = 'pendiente' OR r.estado = 'aprobada')) AS reservados,
    (l.cantidad - 
     (SELECT COUNT(*) FROM prestamos p WHERE p.libro_id = l.id AND p.estado = 'activo') -
     (SELECT COUNT(*) FROM reservas r WHERE r.libro_id = l.id AND (r.estado = 'pendiente' OR r.estado = 'aprobada'))
    ) AS disponibles
FROM libros l
ORDER BY l.titulo ASC;
";

$result = mysqli_query($conexion, $query);
?>

<div class="card p-4 mb-4 shadow">
    <h3 class="mb-4"><i class="zmdi zmdi-collection-bookmark"></i> Inventario de Libros</h3>
    <div class="table-responsive">
        <table id="tblinventario" class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Categoría</th>
                    <th>Total</th>
                    <th>Prestados</th>
                    <th>Reservados</th>
                    <th>Disponibles</th>
                    <th>acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $fila['libro_id'] ?></td>
                        <td><?= htmlspecialchars($fila['titulo']) ?></td>
                        <td><?= htmlspecialchars($fila['autor']) ?></td>
                        <td><?= htmlspecialchars($fila['categoria']) ?></td>
                        <td><?= $fila['cantidad_total'] ?></td>
                        <td><?= $fila['prestados'] ?></td>
                        <td><?= $fila['reservados'] ?></td>
                        <td><?= max($fila['disponibles'], 0) ?></td>
                        <td>
                            <a href="../reports/pdf_inventario.php" class="btn btn-danger" target="_blank">
                                <span class="material-symbols-outlined">
                                    picture_as_pdf
                                </span>
                            </a>

                            <a href="../reports/excel_inventario.php" class="btn btn-success">
                                <i class="zmdi zmdi-file-excel"></i> Exportar Excel
                            </a>


                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php $baseDatos->desconectar(); ?>
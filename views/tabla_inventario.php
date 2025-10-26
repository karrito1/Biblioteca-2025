<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// consulta para obtener el inventario completo de libros con estadisticas
$query = "SELECT 
            l.id,
            l.titulo,
            l.autor,
            l.isbn,
            l.categoria,
            l.cantidad,
            l.disponibilidad,
            l.fecha_registro,
            COALESCE(p.prestamos_activos, 0) as prestamos_activos,
            COALESCE(r.reservas_pendientes, 0) as reservas_pendientes,
            COALESCE(total_prestamos.total, 0) as total_prestamos_historico
          FROM libros l
          LEFT JOIN (
              SELECT libro_id, COUNT(*) as prestamos_activos 
              FROM prestamos 
              WHERE estado = 'activo' 
              GROUP BY libro_id
          ) p ON l.id = p.libro_id
          LEFT JOIN (
              SELECT libro_id, COUNT(*) as reservas_pendientes 
              FROM reservas 
              WHERE estado = 'Pendiente' 
              GROUP BY libro_id
          ) r ON l.id = r.libro_id
          LEFT JOIN (
              SELECT libro_id, COUNT(*) as total 
              FROM prestamos 
              GROUP BY libro_id
          ) total_prestamos ON l.id = total_prestamos.libro_id
          ORDER BY l.titulo ASC";
$result = $baseDatos->efectuarConsulta($query);

// estadisticas generales del inventario
$statsQuery = "SELECT 
                COUNT(*) as total_libros,
                SUM(cantidad) as total_ejemplares,
                SUM(CASE WHEN disponibilidad = 'disponible' THEN cantidad ELSE 0 END) as ejemplares_disponibles,
                SUM(CASE WHEN disponibilidad = 'prestado' THEN 1 ELSE 0 END) as libros_prestados,
                COUNT(DISTINCT categoria) as total_categorias
               FROM libros";
$statsResult = $baseDatos->efectuarConsulta($statsQuery);
$stats = mysqli_fetch_assoc($statsResult);

// libros por categoria
$categoriaQuery = "SELECT categoria, COUNT(*) as cantidad FROM libros GROUP BY categoria ORDER BY cantidad DESC";
$categoriaResult = $baseDatos->efectuarConsulta($categoriaQuery);
$categorias = [];
while ($fila = mysqli_fetch_assoc($categoriaResult)) {
    $categorias[] = $fila;
}
?>

<div class="card p-4 mb-5 shadow">
    <h3 class="mb-4"><i class="zmdi zmdi-archive"></i> Inventario de Libros</h3>

    <!-- estadisticas del inventario -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white text-center">
                <div class="card-body">
                    <h4><?= $stats['total_libros'] ?></h4>
                    <p class="mb-0">titulos</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white text-center">
                <div class="card-body">
                    <h4><?= $stats['total_ejemplares'] ?></h4>
                    <p class="mb-0">Ejemplares</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white text-center">
                <div class="card-body">
                    <h4><?= $stats['ejemplares_disponibles'] ?></h4>
                    <p class="mb-0">Disponibles</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white text-center">
                <div class="card-body">
                    <h4><?= $stats['libros_prestados'] ?></h4>
                    <p class="mb-0">Prestados</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-secondary text-white text-center">
                <div class="card-body">
                    <h4><?= $stats['total_categorias'] ?></h4>
                    <p class="mb-0">categorias</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-dark text-white text-center">
                <div class="card-body">
                    <h4><?= number_format(($stats['ejemplares_disponibles'] / $stats['total_ejemplares']) * 100, 1) ?>%</h4>
                    <p class="mb-0">Disponibilidad</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Controles y filtros -->
    <div class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarLibro">
                    <i class="zmdi zmdi-plus"></i> Agregar Libro
                </button>
            </div>
            <div class="col-md-3">
                <select id="filtroCategoria" class="form-select">
                    <option value="">todas las categorias</option>
                    <?php foreach ($categorias as $categoria) { ?>
                        <option value="<?= htmlspecialchars($categoria['categoria']) ?>">
                            <?= htmlspecialchars($categoria['categoria']) ?> (<?= $categoria['cantidad'] ?>)
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filtroDisponibilidad" class="form-select">
                    <option value="">Todas las disponibilidades</option>
                    <option value="disponible">Disponibles</option>
                    <option value="prestado">Prestados</option>
                    <option value="no disponible">No disponibles</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" id="buscarInventario" class="form-control" placeholder="buscar por titulo, autor o isbn...">
            </div>
        </div>
    </div>

    <!-- Acciones masivas -->
    <div class="mb-3">
        <button class="btn btn-success btn-sm" onclick="exportarInventario('excel')">
            <i class="zmdi zmdi-file-excel"></i> Exportar a Excel
        </button>
        <button class="btn btn-danger btn-sm ms-2" onclick="exportarInventario('pdf')">
            <i class="zmdi zmdi-file-pdf"></i> Exportar a PDF
        </button>
        <button class="btn btn-warning btn-sm ms-2" onclick="generarEtiquetas()">
            <i class="zmdi zmdi-label"></i> Generar Etiquetas
        </button>
        <button class="btn btn-info btn-sm ms-2" onclick="verificarInventario()">
            <i class="zmdi zmdi-search"></i> Verificar Inventario
        </button>
    </div>

    <!-- Tabla de inventario -->
    <div class="table-responsive">
        <table id="tablaInventario" class="table table-striped table-bordered">
            <thead class="table-dark text-center">
                <tr>
                    <th>
                        <input type="checkbox" id="selectAllInventario" class="form-check-input">
                    </th>
                    <th>ID</th>
                    <th>titulo</th>
                    <th>Autor</th>
                    <th>ISBN</th>
                    <th>categoria</th>
                    <th>Cantidad</th>
                    <th>Disponibilidad</th>
                    <th>Prestados</th>
                    <th>Reservas</th>
                    <th>total prestamos</th>
                    <th>Fecha Registro</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($result)) { 
                    // Determinar estado del libro
                    $estado_clase = '';
                    $estado_texto = '';
                    
                    if ($fila['prestamos_activos'] > 0) {
                        $estado_clase = 'table-warning';
                        $estado_texto = 'en prestamo';
                    } elseif ($fila['disponibilidad'] === 'disponible') {
                        $estado_clase = 'table-success';
                        $estado_texto = 'Disponible';
                    } else {
                        $estado_clase = 'table-danger';
                        $estado_texto = 'No disponible';
                    }
                ?>
                    <tr class="<?= $estado_clase ?>">
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input inventario-checkbox" value="<?= $fila['id'] ?>">
                        </td>
                        <td><?= htmlspecialchars($fila['id']) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($fila['titulo']) ?></strong>
                        </td>
                        <td><?= htmlspecialchars($fila['autor']) ?></td>
                        <td>
                            <code><?= htmlspecialchars($fila['isbn']) ?></code>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?= htmlspecialchars($fila['categoria']) ?></span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary"><?= $fila['cantidad'] ?></span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-<?= $fila['disponibilidad'] === 'disponible' ? 'success' : ($fila['disponibilidad'] === 'prestado' ? 'warning' : 'danger') ?>">
                                <?= ucfirst($fila['disponibilidad']) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php if ($fila['prestamos_activos'] > 0) { ?>
                                <span class="badge bg-orange"><?= $fila['prestamos_activos'] ?></span>
                            <?php } else { ?>
                                <span class="text-muted">0</span>
                            <?php } ?>
                        </td>
                        <td class="text-center">
                            <?php if ($fila['reservas_pendientes'] > 0) { ?>
                                <span class="badge bg-info"><?= $fila['reservas_pendientes'] ?></span>
                            <?php } else { ?>
                                <span class="text-muted">0</span>
                            <?php } ?>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-dark"><?= $fila['total_prestamos_historico'] ?></span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($fila['fecha_registro'])) ?></td>
                        <td class="text-center">
                            <span class="badge bg-<?= $estado_texto === 'disponible' ? 'success' : ($estado_texto === 'en prestamo' ? 'warning' : 'danger') ?>">
                                <?= $estado_texto ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button class="btn btn-info btn-sm btnVerDetalleLibro" 
                                        data-id="<?= $fila['id'] ?>"
                                        title="Ver detalles">
                                    <i class="zmdi zmdi-eye"></i>
                                </button>
                                <button class="btn btn-primary btn-sm btnEditarLibro" 
                                        data-id="<?= $fila['id'] ?>"
                                        title="Editar">
                                    <i class="zmdi zmdi-edit"></i>
                                </button>
                                <?php if ($fila['prestamos_activos'] == 0) { ?>
                                    <button class="btn btn-success btn-sm btnPrestarLibro" 
                                            data-id="<?= $fila['id'] ?>"
                                            data-titulo="<?= htmlspecialchars($fila['titulo']) ?>"
                                            title="crear prestamo">
                                        <i class="zmdi zmdi-calendar-check"></i>
                                    </button>
                                <?php } ?>
                                <button class="btn btn-warning btn-sm btnHistorialLibro" 
                                        data-id="<?= $fila['id'] ?>"
                                        title="Ver historial">
                                    <i class="zmdi zmdi-time"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para detalles del libro -->
<div class="modal fade" id="modalDetalleLibro" tabindex="-1" aria-labelledby="modalDetalleLibroLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleLibroLabel">
                    <i class="zmdi zmdi-book"></i> Detalles del Libro
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body" id="contenidoDetalleLibro">
                <!-- se carga dinamicamente -->
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para historial del libro -->
<div class="modal fade" id="modalHistorialLibro" tabindex="-1" aria-labelledby="modalHistorialLibroLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalHistorialLibroLabel">
                    <i class="zmdi zmdi-time"></i> Historial del Libro
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body" id="contenidoHistorialLibro">
                <!-- se carga dinamicamente -->
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php $baseDatos->desconectar(); ?>
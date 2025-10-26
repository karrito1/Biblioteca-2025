<?php
session_start();
require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

if ($_SESSION['roles'] === 'ADMINISTRADOR') {
    $query = "SELECT p.id, p.fecha_prestamo, p.fecha_devolucion, p.estado,
                     u.nombre as usuario_nombre, u.email as usuario_email, u.telefono as usuario_telefono,
                     l.titulo as libro_titulo, l.autor as libro_autor, l.isbn as libro_isbn
              FROM prestamos p
              INNER JOIN usuarios u ON p.usuario_id = u.id
              INNER JOIN libros l ON p.libro_id = l.id
              ORDER BY p.fecha_prestamo DESC";
    $result = $baseDatos->efectuarConsulta($query);
    
    $usuarios_disponibles = [];
    $usuariosQuery = "SELECT id, nombre, email FROM usuarios WHERE estado = 'activo' AND Roles = 'CLIENTE' ORDER BY nombre ASC";
    $usuarios_result = $baseDatos->efectuarConsulta($usuariosQuery);
    while ($fila = mysqli_fetch_assoc($usuarios_result)) {
        $usuarios_disponibles[] = $fila;
    }
    
    $libros_disponibles = [];
    $librosQuery = "SELECT id, titulo, autor, isbn, categoria FROM libros WHERE disponibilidad = 'disponible' AND cantidad > 0 ORDER BY titulo ASC";
    $libros_result = $baseDatos->efectuarConsulta($librosQuery);
    while ($fila = mysqli_fetch_assoc($libros_result)) {
        $libros_disponibles[] = $fila;
    }
} else {
    $query = "SELECT 
                p.id,
                p.fecha_prestamo,
                p.fecha_devolucion,
                p.estado,
                l.titulo as libro_titulo,
                l.autor as libro_autor,
                l.isbn as libro_isbn,
                l.categoria as libro_categoria
            FROM prestamos p
            INNER JOIN libros l ON p.libro_id = l.id
            WHERE p.usuario_id = ?
            ORDER BY p.fecha_prestamo DESC";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $_SESSION['usuario_id']);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<div class="card p-4 mb-5 shadow" style="min-height: calc(100vh - 200px); background: white;">
    <h3 class="mb-4"><i class="zmdi zmdi-calendar-check"></i> gestion de prestamos</h3>

    <?php if ($_SESSION['roles'] === 'ADMINISTRADOR') { ?>
        <div class="mb-4">
            <div class="alert alert-warning">
                <i class="zmdi zmdi-info"></i> Vista de solo lectura - Funciones de gestión temporalmente deshabilitadas para evitar errores
            </div>
        </div>
    <?php } ?>

    <!-- Filtros -->
    <div class="row mb-3">
        <div class="col-md-6">
            <select id="filtroEstado" class="form-select">
                <option value="">Todos los estados</option>
                <option value="activo">Activos</option>
                <option value="devuelto">Devueltos</option>
                <option value="vencido">Vencidos</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" id="buscarPrestamo" class="form-control" placeholder="Buscar por usuario o libro...">
        </div>
    </div>

    <!-- tabla de prestamos -->
    <div class="table-responsive">
        <table id="tablaPrestamos" class="table table-striped table-bordered">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <?php if ($_SESSION['roles'] === 'ADMINISTRADOR') { ?>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>telefono</th>
                    <?php } ?>
                    <th>Libro</th>
                    <th>Autor</th>
                    <th>ISBN</th>
                    <th>fecha prestamo</th>
                    <th>fecha devolucion</th>
                    <th>Estado</th>
                    <th>dias restantes</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($result)) { 
                    $fecha_devolucion = new DateTime($fila['fecha_devolucion']);
                    $fecha_actual = new DateTime();
                    $diferencia = $fecha_actual->diff($fecha_devolucion);
                    $dias_restantes = $fecha_devolucion > $fecha_actual ? $diferencia->days : -$diferencia->days;
                    
                    // determinar clase css segun el estado
                    $clase_estado = '';
                    if ($fila['estado'] === 'vencido' || ($fila['estado'] === 'activo' && $dias_restantes < 0)) {
                        $clase_estado = 'table-danger';
                    } elseif ($fila['estado'] === 'activo' && $dias_restantes <= 3) {
                        $clase_estado = 'table-warning';
                    } elseif ($fila['estado'] === 'devuelto') {
                        $clase_estado = 'table-success';
                    }
                ?>
                    <tr class="<?= $clase_estado ?>">
                        <td><?= htmlspecialchars($fila['id']) ?></td>
                        <?php if ($_SESSION['roles'] === 'ADMINISTRADOR') { ?>
                            <td><?= htmlspecialchars($fila['usuario_nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['usuario_email']) ?></td>
                            <td><?= htmlspecialchars($fila['usuario_telefono']) ?></td>
                        <?php } ?>
                        <td><?= htmlspecialchars($fila['libro_titulo']) ?></td>
                        <td><?= htmlspecialchars($fila['libro_autor']) ?></td>
                        <td><?= htmlspecialchars($fila['libro_isbn']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($fila['fecha_prestamo'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($fila['fecha_devolucion'])) ?></td>
                        <td>
                            <span class="badge bg-<?= $fila['estado'] === 'activo' ? 'primary' : ($fila['estado'] === 'devuelto' ? 'success' : 'danger') ?>">
                                <?= ucfirst($fila['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($fila['estado'] === 'activo') { ?>
                                <span class="badge bg-<?= $dias_restantes < 0 ? 'danger' : ($dias_restantes <= 3 ? 'warning' : 'info') ?>">
                                    <?= $dias_restantes < 0 ? 'vencido (' . abs($dias_restantes) . ' dias)' : $dias_restantes . ' dias' ?>
                                </span>
                            <?php } else { ?>
                                <span class="text-muted">N/A</span>
                            <?php } ?>
                        </td>
                        <td class="text-center">
                            <!-- Solo mostrar información, sin acciones interactivas -->
                            <span class="badge bg-secondary">
                                <i class="zmdi zmdi-info"></i> Vista
                            </span>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($_SESSION['roles'] === 'ADMINISTRADOR') { ?>
    <!-- modal para registrar nuevo prestamo -->
    <div class="modal fade" id="modalRegistrarPrestamo" tabindex="-1" aria-labelledby="modalRegistrarPrestamoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRegistrarPrestamoLabel">
                        <i class="zmdi zmdi-plus"></i> registrar nuevo prestamo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <form id="formRegistrarPrestamo">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="usuario_id" class="form-label">Usuario</label>
                                    <select class="form-select" id="usuario_id" name="usuario_id" required>
                                        <option value="">Seleccionar usuario...</option>
                                        <?php foreach ($usuarios_disponibles as $usuario) { ?>
                                            <option value="<?= $usuario['id'] ?>">
                                                <?= htmlspecialchars($usuario['nombre']) ?> - <?= htmlspecialchars($usuario['email']) ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="libro_id" class="form-label">Libro</label>
                                    <select class="form-select" id="libro_id" name="libro_id" required>
                                        <option value="">Seleccionar libro...</option>
                                        <?php foreach ($libros_disponibles as $libro) { ?>
                                            <option value="<?= $libro['id'] ?>">
                                                <?= htmlspecialchars($libro['titulo']) ?> - <?= htmlspecialchars($libro['autor']) ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_devolucion" class="form-label">fecha de devolucion</label>
                                    <input type="date" class="form-control" id="fecha_devolucion" name="fecha_devolucion" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">dias de prestamo</label>
                                    <div class="btn-group d-flex" role="group">
                                        <input type="radio" class="btn-check" name="dias_prestamo" id="dias7" value="7">
                                        <label class="btn btn-outline-primary" for="dias7">7 dias</label>
                                        
                                        <input type="radio" class="btn-check" name="dias_prestamo" id="dias15" value="15" checked>
                                        <label class="btn btn-outline-primary" for="dias15">15 dias</label>
                                        
                                        <input type="radio" class="btn-check" name="dias_prestamo" id="dias30" value="30">
                                        <label class="btn btn-outline-primary" for="dias30">30 dias</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones (opcional)</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">registrar prestamo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>

<!-- modal para renovar prestamo -->
<div class="modal fade" id="modalRenovarPrestamo" tabindex="-1" aria-labelledby="modalRenovarPrestamoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRenovarPrestamoLabel">
                    <i class="zmdi zmdi-refresh"></i> renovar prestamo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="formRenovarPrestamo">
                <div class="modal-body">
                    <input type="hidden" id="prestamo_id_renovar" name="prestamo_id">
                    
                    <div class="mb-3">
                        <label for="nueva_fecha_devolucion" class="form-label">nueva fecha de devolucion</label>
                        <input type="date" class="form-control" id="nueva_fecha_devolucion" name="nueva_fecha_devolucion" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Extender por</label>
                        <div class="btn-group d-flex" role="group">
                            <input type="radio" class="btn-check" name="extender_dias" id="ext7" value="7">
                            <label class="btn btn-outline-primary" for="ext7">7 dias</label>
                            
                            <input type="radio" class="btn-check" name="extender_dias" id="ext15" value="15" checked>
                            <label class="btn btn-outline-primary" for="ext15">15 dias</label>
                            
                            <input type="radio" class="btn-check" name="extender_dias" id="ext30" value="30">
                            <label class="btn btn-outline-primary" for="ext30">30 dias</label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Renovar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal para ver detalles del prestamo -->
<div class="modal fade" id="modalDetallePrestamo" tabindex="-1" aria-labelledby="modalDetallePrestamoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetallePrestamoLabel">
                    <i class="zmdi zmdi-eye"></i> detalles del prestamo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body" id="contenidoDetallePrestamo">
                <!-- se carga dinamicamente -->
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
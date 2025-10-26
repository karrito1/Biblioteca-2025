<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// consulta para obtener las reservas con informacion de usuario y libro
$query = "SELECT 
            r.id, 
            r.fecha_reserva, 
            r.estado,
            u.nombre as usuario_nombre,
            u.email as usuario_email,
            u.telefono as usuario_telefono,
            l.titulo as libro_titulo,
            l.autor as libro_autor,
            l.isbn as libro_isbn,
            l.categoria as libro_categoria
          FROM reservas r
          INNER JOIN usuarios u ON r.usuario_id = u.id
          INNER JOIN libros l ON r.libro_id = l.id
          ORDER BY r.fecha_reserva DESC";
$result = $baseDatos->efectuarConsulta($query);
?>

<div class="card p-4 mb-5 shadow">
    <h3 class="mb-4"><i class="zmdi zmdi-calendar"></i> gestion de reservas</h3>

    <div class="mb-4">
        <div class="alert alert-info">
            <i class="zmdi zmdi-info"></i> Vista de solo lectura - Gestión de reservas simplificada
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-3">
        <div class="col-md-4">
            <select id="filtroEstadoReserva" class="form-select">
                <option value="">Todos los estados</option>
                <option value="Pendiente">Pendientes</option>
                <option value="Aprobada">Aprobadas</option>
                <option value="Rechazada">Rechazadas</option>
            </select>
        </div>
        <div class="col-md-4">
            <input type="date" id="fechaDesde" class="form-control" placeholder="Fecha desde">
        </div>
        <div class="col-md-4">
            <input type="text" id="buscarReserva" class="form-control" placeholder="Buscar por usuario o libro...">
        </div>
    </div>

    <!-- Tabla de reservas -->
    <div class="table-responsive">
        <table id="tablaReservas" class="table table-striped table-bordered">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Libro</th>
                    <th>Autor</th>
                    <th>categoria</th>
                    <th>Fecha Reserva</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($result)) { 
                    // determinar clase css segun el estado
                    $clase_estado = '';
                    if ($fila['estado'] === 'Pendiente') {
                        $clase_estado = 'table-warning';
                    } elseif ($fila['estado'] === 'Aprobada') {
                        $clase_estado = 'table-success';
                    } elseif ($fila['estado'] === 'Rechazada') {
                        $clase_estado = 'table-danger';
                    }
                ?>
                    <tr class="<?= $clase_estado ?>">
                        <td><?= htmlspecialchars($fila['id']) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($fila['usuario_nombre']) ?></strong>
                            <br>
                            <small class="text-muted"><?= htmlspecialchars($fila['usuario_telefono']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($fila['usuario_email']) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($fila['libro_titulo']) ?></strong>
                            <br>
                            <small class="text-muted">ISBN: <?= htmlspecialchars($fila['libro_isbn']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($fila['libro_autor']) ?></td>
                        <td>
                            <span class="badge bg-secondary"><?= htmlspecialchars($fila['libro_categoria']) ?></span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($fila['fecha_reserva'])) ?></td>
                        <td>
                            <span class="badge bg-<?= $fila['estado'] === 'Pendiente' ? 'warning' : ($fila['estado'] === 'Aprobada' ? 'success' : 'danger') ?>">
                                <?= ucfirst($fila['estado']) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <!-- Solo mostrar estado, sin acciones interactivas -->
                            <span class="badge bg-info">
                                <i class="zmdi zmdi-info"></i> Solo lectura
                            </span>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para registrar nueva reserva -->
<div class="modal fade" id="modalRegistrarReserva" tabindex="-1" aria-labelledby="modalRegistrarReservaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistrarReservaLabel">
                    <i class="zmdi zmdi-plus"></i> Registrar Nueva Reserva
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="formRegistrarReserva">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario_id_reserva" class="form-label">Usuario</label>
                                <select class="form-select" id="usuario_id_reserva" name="usuario_id" required>
                                    <option value="">Seleccionar usuario...</option>
                                    <!-- se carga dinamicamente -->
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="libro_id_reserva" class="form-label">Libro</label>
                                <select class="form-select" id="libro_id_reserva" name="libro_id" required>
                                    <option value="">Seleccionar libro...</option>
                                    <!-- se carga dinamicamente -->
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones_reserva" class="form-label">Observaciones (opcional)</label>
                        <textarea class="form-control" id="observaciones_reserva" name="observaciones" rows="3"></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar Reserva</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver detalles de la reserva -->
<div class="modal fade" id="modalDetalleReserva" tabindex="-1" aria-labelledby="modalDetalleReservaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleReservaLabel">
                    <i class="zmdi zmdi-eye"></i> Detalles de la Reserva
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body" id="contenidoDetalleReserva">
                <!-- se carga dinamicamente -->
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php $baseDatos->desconectar(); ?>
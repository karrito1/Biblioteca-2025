<?php
session_start();
require_once("../models/Prestamo.php");

// verificar autenticacion
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php?error=1");
    exit();
}

$prestamo = new Prestamo();
$prestamos = $prestamo->obtenerPrestamosPorUsuario($_SESSION['usuario_id']);
?>

<div class="card p-4 mb-5 shadow">
    <h3 class="mb-4"><i class="zmdi zmdi-account-box"></i> mis prestamos</h3>

    <!-- estadisticas personales -->
    <div class="row mb-4">
        <?php 
        $activos = count(array_filter($prestamos, function($p) { return $p['estado'] === 'activo'; }));
        $devueltos = count(array_filter($prestamos, function($p) { return $p['estado'] === 'devuelto'; }));
        $vencidos = 0;
        foreach ($prestamos as $p) {
            if ($p['estado'] === 'activo' && strtotime($p['fecha_devolucion']) < time()) {
                $vencidos++;
            }
        }
        ?>
        <div class="col-md-3">
            <div class="card bg-primary text-white text-center">
                <div class="card-body">
                    <h4><?= $activos ?></h4>
                    <p class="mb-0">prestamos activos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white text-center">
                <div class="card-body">
                    <h4><?= $devueltos ?></h4>
                    <p class="mb-0">Libros Devueltos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white text-center">
                <div class="card-body">
                    <h4><?= $vencidos ?></h4>
                    <p class="mb-0">prestamos vencidos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white text-center">
                <div class="card-body">
                    <h4><?= count($prestamos) ?></h4>
                    <p class="mb-0">total prestamos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros para cliente -->
    <div class="row mb-3">
        <div class="col-md-6">
            <select id="filtroEstadoCliente" class="form-select">
                <option value="">todos mis prestamos</option>
                <option value="activo">prestamos activos</option>
                <option value="devuelto">Libros devueltos</option>
                <option value="vencido">prestamos vencidos</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" id="buscarMiPrestamo" class="form-control" placeholder="buscar en mis prestamos...">
        </div>
    </div>

    <?php if (empty($prestamos)) { ?>
        <div class="alert alert-info text-center">
            <i class="zmdi zmdi-info"></i>
            <h5>no tienes prestamos registrados</h5>
            <p>cuando realices prestamos de libros, apareceran aqui.</p>
            <a href="#" class="btn btn-primary" onclick="buscarLibros()">
                <i class="zmdi zmdi-search"></i> Buscar Libros
            </a>
        </div>
    <?php } else { ?>
        <!-- tabla de prestamos del cliente -->
        <div class="table-responsive">
            <table id="tablaMisPrestamos" class="table table-striped table-bordered">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Libro</th>
                        <th>Autor</th>
                        <th>categoria</th>
                        <th>fecha prestamo</th>
                        <th>fecha devolucion</th>
                        <th>Estado</th>
                        <th>dias restantes</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prestamos as $fila) { 
                        $fecha_devolucion = new DateTime($fila['fecha_devolucion']);
                        $fecha_actual = new DateTime();
                        $diferencia = $fecha_actual->diff($fecha_devolucion);
                        $dias_restantes = $fecha_devolucion > $fecha_actual ? $diferencia->days : -$diferencia->days;
                        
                        // determinar clase css segun el estado
                        $clase_estado = '';
                        $estado_real = $fila['estado'];
                        if ($fila['estado'] === 'activo' && $dias_restantes < 0) {
                            $clase_estado = 'table-danger';
                            $estado_real = 'vencido';
                        } elseif ($fila['estado'] === 'activo' && $dias_restantes <= 3) {
                            $clase_estado = 'table-warning';
                        } elseif ($fila['estado'] === 'devuelto') {
                            $clase_estado = 'table-success';
                        }
                    ?>
                        <tr class="<?= $clase_estado ?>" data-estado="<?= $estado_real ?>">
                            <td>
                                <strong><?= htmlspecialchars($fila['libro_titulo']) ?></strong>
                                <br>
                                <small class="text-muted">ISBN: <?= htmlspecialchars($fila['libro_isbn']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($fila['libro_autor']) ?></td>
                            <td>
                                <span class="badge bg-secondary"><?= htmlspecialchars($fila['libro_categoria'] ?? 'sin categoria') ?></span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($fila['fecha_prestamo'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($fila['fecha_devolucion'])) ?></td>
                            <td>
                                <span class="badge bg-<?= $estado_real === 'activo' ? 'primary' : ($estado_real === 'devuelto' ? 'success' : 'danger') ?>">
                                    <?= ucfirst($estado_real) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($fila['estado'] === 'activo') { ?>
                                    <span class="badge bg-<?= $dias_restantes < 0 ? 'danger' : ($dias_restantes <= 3 ? 'warning' : 'info') ?>">
                                        <?php if ($dias_restantes < 0) { ?>
                                            <i class="zmdi zmdi-alert-triangle"></i> vencido hace <?= abs($dias_restantes) ?> dias
                                        <?php elseif ($dias_restantes === 0) { ?>
                                            <i class="zmdi zmdi-time"></i> Vence hoy
                                        <?php elseif ($dias_restantes <= 3) { ?>
                                            <i class="zmdi zmdi-time"></i> <?= $dias_restantes ?> dias restantes
                                        <?php else { ?>
                                            <?= $dias_restantes ?> dias
                                        <?php } ?>
                                    </span>
                                <?php } else { ?>
                                    <span class="text-muted">N/A</span>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <?php if ($fila['estado'] === 'activo') { ?>
                                    <button class="btn btn-warning btn-sm btnSolicitarRenovacion" 
                                            data-id="<?= $fila['id'] ?>"
                                            data-fecha="<?= $fila['fecha_devolucion'] ?>"
                                            data-libro="<?= htmlspecialchars($fila['libro_titulo']) ?>"
                                            title="solicitar renovacion">
                                        <i class="zmdi zmdi-refresh"></i>
                                    </button>
                                <?php } ?>
                                
                                <button class="btn btn-info btn-sm btnVerDetalleCliente" 
                                        data-id="<?= $fila['id'] ?>"
                                        title="Ver detalles">
                                    <i class="zmdi zmdi-eye"></i>
                                </button>
                                
                                <?php if ($fila['estado'] === 'activo') { ?>
                                    <button class="btn btn-primary btn-sm btnRecordarDevolucion" 
                                            data-libro="<?= htmlspecialchars($fila['libro_titulo']) ?>"
                                            data-fecha="<?= $fila['fecha_devolucion'] ?>"
                                            title="recordatorio de devolucion">
                                        <i class="zmdi zmdi-notifications"></i>
                                    </button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>

<!-- modal para solicitar renovacion -->
<div class="modal fade" id="modalSolicitarRenovacion" tabindex="-1" aria-labelledby="modalSolicitarRenovacionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSolicitarRenovacionLabel">
                    <i class="zmdi zmdi-refresh"></i> solicitar renovacion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="formSolicitarRenovacion">
                <div class="modal-body">
                    <input type="hidden" id="prestamo_id_cliente" name="prestamo_id">
                    
                    <div class="alert alert-info">
                        <i class="zmdi zmdi-info"></i>
                        <strong>Libro:</strong> <span id="libroRenovacion"></span><br>
                        <strong>fecha actual de devolucion:</strong> <span id="fechaActualDevolucion"></span>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nueva_fecha_devolucion_cliente" class="form-label">nueva fecha de devolucion deseada</label>
                        <input type="date" class="form-control" id="nueva_fecha_devolucion_cliente" name="nueva_fecha_devolucion" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">opciones rapidas</label>
                        <div class="btn-group d-flex" role="group">
                            <input type="radio" class="btn-check" name="extender_dias_cliente" id="extCliente7" value="7">
                            <label class="btn btn-outline-primary" for="extCliente7">+7 dias</label>
                            
                            <input type="radio" class="btn-check" name="extender_dias_cliente" id="extCliente15" value="15" checked>
                            <label class="btn btn-outline-primary" for="extCliente15">+15 dias</label>
                            
                            <input type="radio" class="btn-check" name="extender_dias_cliente" id="extCliente30" value="30">
                            <label class="btn btn-outline-primary" for="extCliente30">+30 dias</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="motivo_renovacion" class="form-label">motivo de la renovacion (opcional)</label>
                        <textarea class="form-control" id="motivo_renovacion" name="motivo_renovacion" rows="3" placeholder="ej: necesito mas tiempo para completar la lectura..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">solicitar renovacion</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal para recordatorio de devolucion -->
<div class="modal fade" id="modalRecordatorio" tabindex="-1" aria-labelledby="modalRecordatorioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRecordatorioLabel">
                    <i class="zmdi zmdi-notifications"></i> recordatorio de devolucion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body" id="contenidoRecordatorio">
                <!-- se carga dinamicamente -->
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="programarRecordatorio()">
                    <i class="zmdi zmdi-alarm"></i> Programar Recordatorio
                </button>
            </div>
        </div>
    </div>
</div>
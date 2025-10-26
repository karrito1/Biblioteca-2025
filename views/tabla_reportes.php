<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../models/MySQL.php");
require_once("../includes/reports_functions.php");

// Conectar a la base de datos
$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Obtener todas las estadisticas usando funciones separadas
$estadisticas = obtenerEstadisticasGenerales($baseDatos);
$libros_populares = obtenerLibrosPopulares($baseDatos, 10);
$usuarios_activos = obtenerUsuariosActivos($baseDatos, 10);
$prestamos_mensuales = obtenerPrestamosMensuales($baseDatos, 6);
$datos_prestamos = obtenerDatosPrestamos($baseDatos);
$datos_reservas = obtenerDatosReservas($baseDatos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reportes - Biblioteca SENAP</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Material Design Icons -->
    <link rel="stylesheet" href="/Biblioteca-2025/css/material-design-iconic-font.min.css">
    
    <!-- Blue Admin Theme CSS -->
    <link rel="stylesheet" href="/Biblioteca-2025/css/blue-admin-theme.css">
</head>
<body>

<div class="card p-4 mb-5 shadow">
    <h3 class="mb-4"><i class="zmdi zmdi-trending-up"></i> reportes y estadisticas</h3>

    <!-- botones para generar reportes -->
    <div class="mb-4">
        <button class="btn btn-danger me-2" onclick="generarReportePDF('prestamos')">
            <i class="zmdi zmdi-file-pdf"></i> reporte de prestamos (pdf)
        </button>
        <button class="btn btn-success me-2" onclick="exportarExcel('prestamos')">
            <i class="zmdi zmdi-file-excel"></i> exportar prestamos (excel)
        </button>
        <button class="btn btn-primary me-2" onclick="generarReportePDF('usuarios')">
            <i class="zmdi zmdi-file-pdf"></i> reporte usuarios (pdf)
        </button>
        <button class="btn btn-info" onclick="exportarExcel('usuarios')">
            <i class="zmdi zmdi-file-excel"></i> exportar usuarios (excel)
        </button>
    </div>

    <!-- estadisticas generales -->
    <?php echo generarTarjetasEstadisticas($estadisticas); ?>

    <!-- filtros para reportes personalizados -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="zmdi zmdi-filter-list"></i> reportes personalizados</h5>
        </div>
        <div class="card-body">
            <form id="formReportePersonalizado">
                <div class="row">
                    <div class="col-md-4">
                        <label for="tipoReporte" class="form-label">tipo de reporte</label>
                        <select class="form-select" id="tipoReporte" name="tipo">
                            <option value="">seleccionar...</option>
                            <option value="prestamos">prestamos</option>
                            <option value="usuarios">usuarios</option>
                            <option value="libros">libros</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fechaInicio" class="form-label">fecha inicio</label>
                        <input type="date" class="form-control" id="fechaInicio" name="fecha_inicio">
                    </div>
                    <div class="col-md-3">
                        <label for="fechaFin" class="form-label">fecha fin</label>
                        <input type="date" class="form-control" id="fechaFin" name="fecha_fin">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block">
                            <i class="zmdi zmdi-download"></i> generar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- graficos y estadisticas -->
    <div class="row">
        <!-- libros mas prestados -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6><i class="zmdi zmdi-chart-donut"></i> libros mas prestados</h6>
                </div>
                <div class="card-body">
                    <?php echo generarTablaLibrosPopulares($libros_populares, 5); ?>
                </div>
            </div>
        </div>

        <!-- usuarios mas activos -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6><i class="zmdi zmdi-account-box"></i> usuarios mas activos</h6>
                </div>
                <div class="card-body">
                    <?php echo generarTablaUsuariosActivos($usuarios_activos, 5); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- grafico de prestamos vs reservas -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6><i class="zmdi zmdi-chart-donut"></i> distribucion de prestamos</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartPrestamos" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6><i class="zmdi zmdi-chart-donut"></i> estado de reservas</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartReservas" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- grafico de prestamos mensuales -->
    <div class="card mb-4">
        <div class="card-header">
            <h6><i class="zmdi zmdi-chart"></i> prestamos por mes (ultimos 6 meses)</h6>
        </div>
        <div class="card-body">
            <canvas id="chartPrestamosMensuales" height="100"></canvas>
        </div>
    </div>

    <!-- Tabla de actividad reciente -->
    <div class="card">
        <div class="card-header">
            <h6><i class="zmdi zmdi-time"></i> actividad reciente</h6>
        </div>
        <div class="card-body">
            <div id="actividadReciente">
                <!-- se carga dinamicamente -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// pasar datos del servidor a JavaScript
window.prestamosData = <?= json_encode($prestamos_mensuales) ?>;
window.datosPrestamos = <?= json_encode($datos_prestamos) ?>;
window.datosReservas = <?= json_encode($datos_reservas) ?>;
</script>
<script src="../js/tabla_reportes.js"></script>

</body>
</html>

<?php $baseDatos->desconectar(); ?>
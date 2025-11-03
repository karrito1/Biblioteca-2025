<?php
session_start();
require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

if (!isset($_SESSION['usuario_id']) || $_SESSION['roles'] !== 'ADMINISTRADOR') {
    header("Location: ../index.php?error=1");
    exit();
} else {
    $usuario_nombre = $_SESSION['email'];
    $usuario_rol = $_SESSION['roles'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reportes - Biblioteca SENAP 2025</title>

    <link rel="Shortcut Icon" type="image/x-icon" href="/Biblioteca-2025/assets/icons/book.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Biblioteca-2025/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/style.css">
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.40/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.min.js"></script>
</head>

<body>
    <div class="navbar-lateral full-reset">
        <div class="visible-xs font-movile-menu mobile-menu-button"></div>
        <div class="full-reset container-menu-movile nav-lateral-scroll">
            <div class="logo full-reset all-tittles">
                <i class="visible-xs zmdi zmdi-close pull-left mobile-menu-button"></i>
                Sistema Bibliotecario
            </div>

            <div class="nav-lateral-divider full-reset"></div>
            <div class="full-reset" style="padding: 10px 0; color:#fff;">
                <figure>
                    <img src="/Biblioteca-2025/assets/img/logo1.jpg" alt="Biblioteca" class="img-responsive center-box " style="width:55%">
                </figure>
                <p class="text-center" style="padding-top: 15px;">Biblioteca SENAP</p>
                <p class="text-center"><strong>Rol:</strong> Administrador</p>
            </div>

            <div class="nav-lateral-divider full-reset"></div>

            <div class="full-reset nav-lateral-list-menu">
                <ul class="list-unstyled">
                    <li><a href="/Biblioteca-2025/views/dashboard.php"><i class="zmdi zmdi-home"></i>&nbsp;&nbsp; Inicio</a></li>
                    <li><a href="/Biblioteca-2025/views/report.php"><i class="zmdi zmdi-trending-up"></i>&nbsp;&nbsp; Reportes</a></li>
                    <li><a href="/Biblioteca-2025/views/historial.php"><i class="zmdi zmdi-time"></i>&nbsp;&nbsp; Historial</a></li>
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalCerrarSesion">
                            <i class="zmdi zmdi-power"></i>&nbsp;&nbsp; Cerrar sesion
                        </a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="content-page-container full-reset custom-scroll-containers">
        <nav class="navbar-user-top full-reset">
            <ul class="list-unstyled full-reset">
                <li style="color:#fff; cursor:default;">
                    <i class="zmdi zmdi-account-circle"></i>
                    &nbsp; Bienvenido, <?php echo htmlspecialchars($usuario_nombre); ?>
                </li>
            </ul>
        </nav>

        <div class="container">
            <div class="page-header">
                <h1 class="all-tittles">Panel de <small>Reportes</small></h1>
            </div>
        </div>

        <section class="full-reset text-center" style="padding: 40px 0;">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <h3 class="mb-4">Generar Reportes</h3>
                        
                        <div class="card p-4">
                            <h5 class="mb-3">Usuarios</h5>
                            <div class="btn-group w-100 mb-3" role="group">
                                <button type="button" class="btn btn-primary btn-reporte" data-tipo="usuarios" data-formato="excel">
                                    <i class="zmdi zmdi-file-excel"></i> Excel
                                </button>
                                <button type="button" class="btn btn-danger btn-reporte" data-tipo="usuarios" data-formato="pdf">
                                    <i class="zmdi zmdi-file-pdf"></i> PDF
                                </button>
                            </div>

                            <h5 class="mb-3 mt-4">Libros</h5>
                            <div class="btn-group w-100 mb-3" role="group">
                                <button type="button" class="btn btn-primary btn-reporte" data-tipo="libros" data-formato="excel">
                                    <i class="zmdi zmdi-file-excel"></i> Excel
                                </button>
                                <button type="button" class="btn btn-danger btn-reporte" data-tipo="libros" data-formato="pdf">
                                    <i class="zmdi zmdi-file-pdf"></i> PDF
                                </button>
                            </div>

                            <h5 class="mb-3 mt-4">Prestamos</h5>
                            <div class="btn-group w-100 mb-3" role="group">
                                <button type="button" class="btn btn-primary btn-reporte" data-tipo="prestamos" data-formato="excel">
                                    <i class="zmdi zmdi-file-excel"></i> Excel
                                </button>
                                <button type="button" class="btn btn-danger btn-reporte" data-tipo="prestamos" data-formato="pdf">
                                    <i class="zmdi zmdi-file-pdf"></i> PDF
                                </button>
                            </div>

                            <hr>
                            <a href="/Biblioteca-2025/views/dashboard.php" class="btn btn-secondary w-100">
                                <i class="zmdi zmdi-arrow-back"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer>
            © 2025 Biblioteca-2025 | Desarrollado por Cristian Villa y Jhoan Morales
        </footer>
    </div>

    <?php include __DIR__ . '/modales/modalCerrarSesion.php'; ?>

    <script src="/Biblioteca-2025/js/alertaCerrar.js"></script>
    <script src="/Biblioteca-2025/js/generarReportes.js"></script>
</body>
</html>

<?php $baseDatos->desconectar(); ?>

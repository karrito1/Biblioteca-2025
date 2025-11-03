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
    <title>Historial de Prestamos - Biblioteca SENAP 2025</title>

    <link rel="Shortcut Icon" type="image/x-icon" href="/Biblioteca-2025/assets/icons/book.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/style.css">
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <h1 class="all-tittles">Historial de <small>Prestamos</small></h1>
            </div>
        </div>

        <section class="full-reset" style="padding: 20px 0;">
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <table id="tablaHistorial" class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Libro</th>
                                    <th>Fecha Prestamo</th>
                                    <th>Fecha Devolucion</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="/Biblioteca-2025/views/dashboard.php" class="btn btn-secondary">
                        <i class="zmdi zmdi-arrow-back"></i> Volver
                    </a>
                </div>
            </div>
        </section>

        <footer>
            © 2025 Biblioteca-2025 | Desarrollado por Cristian Villa y Jhoan Morales
        </footer>
    </div>

    <?php include __DIR__ . '/modales/modalCerrarSesion.php'; ?>

    <script src="/Biblioteca-2025/js/alertaCerrar.js"></script>
    <script src="/Biblioteca-2025/js/historial.js"></script>
</body>
</html>

<?php $baseDatos->desconectar(); ?>

<?php
session_start();
require_once("../models/MySQL.php");
require_once("../includes/dashboard_functions.php");

// Validar sesion
validarSesion();

// Conectar a la base de datos
$baseDatos = new MySQL();
$baseDatos->conectar();

// Obtener estadisticas y datos del usuario
$stats = obtenerEstadisticasDashboard($baseDatos);
$usuarioInfo = obtenerInfoUsuario();
$usuario_nombre = $usuarioInfo['nombre'];
$usuario_rol = $usuarioInfo['rol'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Biblioteca SENAP 2025</title>

    <!-- ====== Metadatos SEO ====== -->
    <meta name="description"
        content="Biblioteca SENAP ofrece acceso a libros, recursos digitales y actividades culturales para fomentar el aprendizaje y la lectura.">
    <meta name="keywords" content="biblioteca, educacion, lectura, libros digitales, cultura">
    <meta name="author" content="Cristian Villa y Jhoan Morales">

    <!-- ====== icono ====== -->
    <link rel="icon" type="image/svg+xml" href="/Biblioteca-2025/assets/icons/book.svg" />

    <!-- ====== Bootstrap ====== -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ====== DataTables ====== -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- ====== Iconos y estilos personalizados ====== -->
    <!-- ====== Iconos y estilos personalizados ====== -->
    <link rel="stylesheet" href="/Biblioteca-2025/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/style.css">
    
    <!-- ====== Estilos para legibilidad con fondo blanco ====== -->

    <link rel="stylesheet" href="/Biblioteca-2025/css/blue-admin-theme.css">
    <!-- jquery debe ir antes que cualquier plugin que dependa de el -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap (usa bundle que incluye Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Scrollbar -->
    <script src="../js/jquery.mCustomScrollbar.concat.min.js"></script>

    <!-- Script principal del sistema -->
    <script src="../js/main.js"></script>
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
                    <img src="/Biblioteca-2025/assets/img/logo1.svg" alt="Biblioteca SENAP" class="img-responsive center-box" style="width:55%; display: block; margin: 0 auto;">
                </figure>
                <p class="text-center" style="padding-top: 15px;">Biblioteca SENAP</p>
                <p class="text-center"><strong>Rol:</strong> <?php echo ucfirst(strtolower($usuario_rol)); ?></p>
            </div>

            <div class="nav-lateral-divider full-reset"></div>

            <div class="full-reset nav-lateral-list-menu">
                <?php echo generarMenuLateral($usuario_rol); ?>
            </div>
        </div>
    </div>
    <!-- CONTENIDO CENTRAL-->
    <div class="content-page-container full-reset custom-scroll-containers">
        <nav class="navbar-user-top full-reset">
            <ul class="list-unstyled full-reset">
                <li style="color:#fff; cursor:default;">
                    <i class="zmdi zmdi-account-circle"></i>
                    &nbsp; Bienvenido, <?php echo htmlspecialchars($usuario_nombre); ?>
                </li>
                <li style="margin-left: auto;">
                    <button class="btn btn-sm btn-outline-light" onclick="limpiarModalesBloqueados()" title="Limpiar modales bloqueados">
                        <i class="zmdi zmdi-refresh"></i>
                    </button>
                </li>
            </ul>
        </nav>


        <div class="container">
            <div class="page-header">
                <h1 class="all-tittles">Panel de <small><?php echo ucfirst(strtolower($usuario_rol)); ?></small></h1>
            </div>

        </div>
        <!-- tarjetas del dashboard -->
        <section class="full-reset text-center" style="padding: 40px 0;">
            <?php echo generarTarjetasDashboard($usuario_rol, $stats); ?>
        </section>
        <div id="tablaUsuariosContainer" class="container-fluid" style="display:none; margin-top:20px; min-height: calc(100vh - 250px);">
        </div>
        <div id="tablaLibrosContainer" class="container-fluid" style="display:none; margin-top:20px; min-height: calc(100vh - 250px);"></div>
        <div id="tablaPrestamosContainer" class="container-fluid" style="display:none; margin-top:20px; min-height: calc(100vh - 250px);"></div>
        <div id="tablaReservasContainer" class="container-fluid" style="display:none; margin-top:20px; min-height: calc(100vh - 250px);"></div>
        <div id="tablaReportesContainer" class="container-fluid" style="display:none; margin-top:20px; min-height: calc(100vh - 250px);"></div>
        <div id="tablaInventarioContainer" class="container-fluid" style="display:none; margin-top:20px; min-height: calc(100vh - 250px);"></div>
        <div id="tablaLibroscliente" class="container-fluid" style="display:none; margin-top:20px; min-height: calc(100vh - 250px);"></div>
        <div id="tablaReservasCliente" class="container-fluid" style="display:none; margin-top:20px; min-height: calc(100vh - 250px);"></div>
        <div id="tablaMisPrestamosContainer" class="container-fluid" style="display:none; margin-top:20px; min-height: calc(100vh - 250px);"></div>

        <footer>
            © 2025 Biblioteca-2025 | Desarrollado por Cristian Villa y Jhoan Morales
        </footer>

    </div>
    <div id="contenedorModal"></div>
    <!-- aca nos dirigimos a la carpeta de los modales -->
    <?php include __DIR__ . '/modales/modalRegistro.php'; ?>
    <?php include __DIR__ . '/modales/modalEliminar.php'; ?>
    <?php include __DIR__ . '/modales/modalCerrarSesion.php'; ?>
    <?php include __DIR__ . '/modales/modalRegistrarLibro.php'; ?>
    <?php include __DIR__ . '/modales/modalRegistrarPrestamo.php'; ?>


    <script src="../js/alertaCerrar.js"></script>
    <script src="../js/alertaRegistro.js"></script>
    <script src="../js/alertaEditar.js"></script>
    <script src="../js/alertaEliminar.js"></script>
    <script src="../js/alertaRegistroLibros.js"></script>
    <script src="../js/alertaPrestamos.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/tabla_usuarios.js"></script>
    <script src="../js/tabla_libros.js"></script>
    <script src="../js/tabla_prestamos.js"></script>
    <script src="../js/tabla_reservas.js"></script>
    <script src="../js/tabla_reportes.js"></script>
    <script src="../js/tabla_inventario.js"></script>
    <script src="../js/librosClientes.js"></script>
    <script src="../js/reservasClientes.js"></script>
    <script src="../js/prestamosClientes.js"></script>
    <script src="../js/dashboard.js"></script>
</body>

</html>
<?php $baseDatos->desconectar(); ?>
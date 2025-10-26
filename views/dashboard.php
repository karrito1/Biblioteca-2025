<?php
session_start();
require_once("../models/MySQL.php");
<<<<<<< HEAD
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
=======

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();
// consulta de usurios registrados
$consultadoUsuarios = "SELECT count(*) as totalUsuarios FROM usuarios";
$resultado0 = $baseDatos->efectuarConsulta($consultadoUsuarios);
$totalUsuarios = mysqli_fetch_assoc($resultado0)['totalUsuarios'];

// consulta de libros registrados
$consultaLibros = "SELECT count(*) as totalLibros FROM libros";
$resultado1 = $baseDatos->efectuarConsulta($consultaLibros);
$totalLibros = mysqli_fetch_assoc($resultado1)['totalLibros'];

//consulta de  reservas pendientes

$consultaReservas = "SELECT count(*) as totalReservas FROM reservas where estado='Pendiente'";
$resultado2 = $baseDatos->efectuarConsulta($consultaReservas);
$totalReservas = mysqli_fetch_assoc($resultado2)['totalReservas'];

//consulta de prestamos activos
$consultaPrestamos = "SELECT count(*) as totalPrestamos FROM prestamos where estado='activo'";
$resultado3 = $baseDatos->efectuarConsulta($consultaPrestamos);
$totalPrestamos = mysqli_fetch_assoc($resultado3)['totalPrestamos'];




if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php?error=1");
    exit();
}
$usuario_nombre = $_SESSION['email'];
$usuario_rol = $_SESSION['roles']; // 'ADMINISTRADOR' o 'CLIENTE'


>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264

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
<<<<<<< HEAD
    <meta name="keywords" content="biblioteca, educacion, lectura, libros digitales, cultura">
    <meta name="author" content="Cristian Villa y Jhoan Morales">

    <!-- ====== icono ====== -->
    <link rel="icon" type="image/svg+xml" href="/Biblioteca-2025/assets/icons/book.svg" />
=======
    <meta name="keywords" content="biblioteca, educación, lectura, libros digitales, cultura">
    <meta name="author" content="Cristian Villa y Jhoan Morales">

    <!-- ====== Ícono ====== -->
    <link rel="Shortcut Icon" type="image/x-icon" href="/Biblioteca-2025/assets/icons/book.ico" />
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264

    <!-- ====== Bootstrap ====== -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ====== DataTables ====== -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- ====== Iconos y estilos personalizados ====== -->
<<<<<<< HEAD
    <!-- ====== Iconos y estilos personalizados ====== -->
    <link rel="stylesheet" href="/Biblioteca-2025/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/style.css">
    
    <!-- ====== Estilos para legibilidad con fondo blanco ====== -->

    <link rel="stylesheet" href="/Biblioteca-2025/css/blue-admin-theme.css">
    <!-- jquery debe ir antes que cualquier plugin que dependa de el -->
=======
    <link rel="stylesheet" href="/Biblioteca-2025/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/style.css">

    <!-- ====== Librerías JS externas ====== -->
    <!-- jQuery debe ir antes que cualquier plugin que dependa de él -->
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap (usa bundle que incluye Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<<<<<<< HEAD
    <!-- Scrollbar -->
    <script src="../js/jquery.mCustomScrollbar.concat.min.js"></script>

    <!-- Script principal del sistema -->
    <script src="../js/main.js"></script>
</head>

=======

    <!-- SweetAlert2 (versión moderna, NO uses sweet-alert.min.js antiguo) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scrollbar -->
    <script src="/Biblioteca-2025/js/jquery.mCustomScrollbar.concat.min.js"></script>

    <!-- Script principal del sistema -->
    <script src="/Biblioteca-2025/js/main.js"></script>
</head>


>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
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
<<<<<<< HEAD
                    <img src="/Biblioteca-2025/assets/img/logo1.svg" alt="Biblioteca SENAP" class="img-responsive center-box" style="width:55%; display: block; margin: 0 auto;">
=======
                    <img src="/Biblioteca-2025/assets/img/logo1.jpg" alt="Biblioteca" class="img-responsive center-box " style="width:55%">
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
                </figure>
                <p class="text-center" style="padding-top: 15px;">Biblioteca SENAP</p>
                <p class="text-center"><strong>Rol:</strong> <?php echo ucfirst(strtolower($usuario_rol)); ?></p>
            </div>

            <div class="nav-lateral-divider full-reset"></div>

            <div class="full-reset nav-lateral-list-menu">
<<<<<<< HEAD
                <?php echo generarMenuLateral($usuario_rol); ?>
            </div>
        </div>
    </div>
    <!-- CONTENIDO CENTRAL-->
=======
                <ul class="list-unstyled">
                    <li><a href="/Biblioteca-2025/views/dashboard.php"><i class="zmdi zmdi-home"></i>&nbsp;&nbsp; Inicio</a></li>

                    <?php if ($usuario_rol === 'ADMINISTRADOR') { ?>
                        <!-- panel administrador -->
                        <li><a href="#" id="btnUsuariosMenu"><i class="zmdi zmdi-book"></i>&nbsp;&nbsp; Registro de usuarios</a></li>
                        <li><a href="#" id="btnLibrosMenu"><i class="zmdi zmdi-calendar"></i>&nbsp;&nbsp; Registro de libros</a></li>
                        <li><a href="/Biblioteca-2025/views/report.php"><i class="zmdi zmdi-trending-up"></i>&nbsp;&nbsp; Reservas</a></li>
                        <li><a href="/Biblioteca-2025/views/report.php"><i class="zmdi zmdi-trending-up"></i>&nbsp;&nbsp; Prestamos</a></li>
                        <li><a href="/Biblioteca-2025/views/report.php"><i class="zmdi zmdi-trending-up"></i>&nbsp;&nbsp; Reportes (PDF/Excel)</a></li>
                        <li><a href="/Biblioteca-2025/views/book.php"><i class="zmdi zmdi-book"></i>&nbsp;&nbsp; Inventario</a></li>


                    <?php } elseif ($usuario_rol === 'CLIENTE') { ?>
                        <!-- panel cliente -->
                        <li><a href="/Biblioteca-2025/views/searchbook.php"><i class="zmdi zmdi-search"></i>&nbsp;&nbsp; Buscar libros</a></li>
                        <li><a href="/Biblioteca-2025/views/myreservations.php"><i class="zmdi zmdi-timer"></i>&nbsp;&nbsp; Mis reservas</a></li>
                        <li><a href="/Biblioteca-2025/views/searchbook.php"><i class="zmdi zmdi-search"></i>&nbsp;&nbsp; Mis préstamos</a></li>
                        <li><a href="/Biblioteca-2025/views/myreservations.php"><i class="zmdi zmdi-timer"></i>&nbsp;&nbsp; Mis datos</a></li>
                    <?php } ?>


                    <li><a href="/Biblioteca-2025/logout.php"><i class="zmdi zmdi-power"></i>&nbsp;&nbsp; Cerrar sesión</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- ======= CONTENIDO CENTRAL ======= -->
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
    <div class="content-page-container full-reset custom-scroll-containers">
        <nav class="navbar-user-top full-reset">
            <ul class="list-unstyled full-reset">
                <li style="color:#fff; cursor:default;">
                    <i class="zmdi zmdi-account-circle"></i>
                    &nbsp; Bienvenido, <?php echo htmlspecialchars($usuario_nombre); ?>
                </li>
<<<<<<< HEAD
                <li style="margin-left: auto;">
                    <button class="btn btn-sm btn-outline-light" onclick="limpiarModalesBloqueados()" title="Limpiar modales bloqueados">
                        <i class="zmdi zmdi-refresh"></i>
                    </button>
                </li>
=======
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
            </ul>
        </nav>


        <div class="container">
            <div class="page-header">
                <h1 class="all-tittles">Panel de <small><?php echo ucfirst(strtolower($usuario_rol)); ?></small></h1>
            </div>

        </div>
<<<<<<< HEAD
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
=======
        <!-- tarjetas de doles -->
        <section class="full-reset text-center" style="padding: 40px 0;">
            <?php if ($usuario_rol === 'ADMINISTRADOR') { ?>
                <!--tarjetas segun administrador -->
                <article class="tile" id="btnUsuarios">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-book"></i></div>
                    <div class="tile-name all-tittles">Usuarios registrados</div>
                    <!-- aca se registra la variables de   de la consulta para mostar en las cards  -->
                    <div class="tile-num full-reset"><?= $totalUsuarios ?></div>
                </article>
                <article class="tile" id ="btnClientes">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-book"></i></div>
                    <div class="tile-name all-tittles">Libros registrados</div>
                    <!-- aca se registra la variables de   de la consulta para mostar en las cards  -->
                    <div class="tile-num full-reset"><?= $totalLibros ?></div>
                </article>
                <article class="tile">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-calendar"></i></div>
                    <div class="tile-name all-tittles">Reservas pendientes</div>
                    <!-- aca se registra la variables de   de la consulta para mostar en las cards  -->
                    <div class="tile-num full-reset"><?= $totalReservas ?></div>
                </article>
                <article class="tile">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-calendar"></i></div>
                    <div class="tile-name all-tittles">Prestamos Activos</div>
                    <!-- aca se registra la variables de   de la consulta para mostar en las cards  -->
                    <div class="tile-num full-reset"><?= $totalPrestamos ?></div>
                </article>
                <article class="tile">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-trending-up"></i></div>
                    <div class="tile-name all-tittles">Reportes</div>
                    <div class="tile-num full-reset">&nbsp;</div>
                </article>
                <!-- tarejtas clientes -->
            <?php } elseif ($usuario_rol === 'CLIENTE') { ?>
                <article class="tile">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-search"></i></div>
                    <div class="tile-name all-tittles">Buscar libros</div>
                </article>
                <article class="tile">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-timer"></i></div>
                    <div class="tile-name all-tittles">Mis reservas</div>
                </article>
                <article class="tile">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-calendar"></i></div>
                    <div class="tile-name all-tittles">Historial de préstamos</div>
                </article>
            <?php } ?>
        </section>
        <div id="tablaUsuariosContainer" class="container" style="display:none; margin-top:30px;">

        </div>
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264

        <footer>
            © 2025 Biblioteca-2025 | Desarrollado por Cristian Villa y Jhoan Morales
        </footer>

    </div>
<<<<<<< HEAD
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
=======
    <!-- aca nos dirigimos a la carpeta de los modales -->
    <?php include __DIR__ . '/modales/modalRegistro.php'; ?>
    <?php include __DIR__ . '/modales/modalEditar.php'; ?>
 <?php include __DIR__ . '/modales/modalEliminar.php'; ?>
 <?php include __DIR__ . '/modales/modalRegistrarLibro.php'; ?>
  <?php include __DIR__ . '/modales/modalCerrarSesion.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/Biblioteca-2025/js/alertaRegistro.js"></script>
        <script src="../js/librosClientes.js"></script>
    <script src="../js/tabla_usuarios.js"></script>
    

>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
</body>

</html>
<?php $baseDatos->desconectar(); ?>
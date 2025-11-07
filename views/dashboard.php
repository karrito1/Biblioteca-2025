<?php
session_start();
require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Validar sesiOn
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php?error=1");
    exit();
} else {
    // Datos de sesiOn
    $usuario_id = $_SESSION['usuario_id'];
    $usuario_nombre = $_SESSION['email'];
    $usuario_rol = $_SESSION['roles'];
    // Normalizar rol para que los modales y vistas puedan usar una variable consistente
    $rol = isset($usuario_rol) ? strtoupper(trim($usuario_rol)) : "";

    // Consulta de usuarios registrados
    $consultadoUsuarios = "SELECT COUNT(*) as totalUsuarios FROM usuarios";
    $resultado0 = $baseDatos->efectuarConsulta($consultadoUsuarios);
    $totalUsuarios = mysqli_fetch_assoc($resultado0)['totalUsuarios'];

    // Consulta de libros registrados
    $consultaLibros = "SELECT COUNT(*) as totalLibros FROM libros";
    $resultado1 = $baseDatos->efectuarConsulta($consultaLibros);
    $totalLibros = mysqli_fetch_assoc($resultado1)['totalLibros'];

    // Consulta de reservas pendientes (todas)
    $consultaReservas = "SELECT COUNT(*) as totalReservas FROM reservas WHERE estado='Pendiente'";
    $resultado2 = $baseDatos->efectuarConsulta($consultaReservas);
    $totalReservas = mysqli_fetch_assoc($resultado2)['totalReservas'];

    // Consulta de reservas pendientes de este cliente
    $consultaReservasCliente = "SELECT COUNT(*) as totalReservasCliente 
                                FROM reservas 
                                WHERE usuario_id = $usuario_id 
                                AND estado='Pendiente'";
    $resultadoReservasCliente = $baseDatos->efectuarConsulta($consultaReservasCliente);
    $totalReservasCliente = mysqli_fetch_assoc($resultadoReservasCliente)['totalReservasCliente'];

    // Consulta de prEstamos activos
    $consultaPrestamos = "SELECT COUNT(*) as totalPrestamos FROM prestamos WHERE estado='activo'";
    $resultado3 = $baseDatos->efectuarConsulta($consultaPrestamos);
    $totalPrestamos = mysqli_fetch_assoc($resultado3)['totalPrestamos'];




    $consultaPrestamoscliente = "SELECT COUNT(*) as totalPrestamosClientes
                         FROM prestamos 
                         WHERE usuario_id = $usuario_id 
                         AND estado='activo'";
    $resultado5 = $baseDatos->efectuarConsulta($consultaPrestamoscliente);
    $totalPrestamosClientes = mysqli_fetch_assoc($resultado5)['totalPrestamosClientes'];

    // Consulta de historial de prEstamos devueltos
    $consultahistorial = "SELECT COUNT(*) as totalHistorial FROM prestamos WHERE estado='devuelto'";
    $resultado4 = $baseDatos->efectuarConsulta($consultahistorial);
    $historialPrestamos = mysqli_fetch_assoc($resultado4)['totalHistorial'];
}
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
    <meta name="keywords" content="biblioteca, educación, lectura, libros digitales, cultura">
    <meta name="author" content="Cristian Villa y Jhoan Morales">

    <!-- ====== Ícono ====== -->
    <link rel="Shortcut Icon" type="image/x-icon" href="/Biblioteca-2025/assets/icons/book.ico" />

    <!-- ====== Bootstrap ====== -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ====== DataTables ====== -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- ====== Iconos y estilos personalizados ====== -->
    <link rel="stylesheet" href="/Biblioteca-2025/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/style.css">

    <!-- ====== Librerías JS externas ====== -->
    <!-- jQuery debe ir antes que cualquier plugin que dependa de él -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap (usa bundle que incluye Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Scrollbar -->
    <script src="/Biblioteca-2025/js/jquery.mCustomScrollbar.concat.min.js"></script>

    <!-- Script principal del sistema -->
    <script src="/Biblioteca-2025/js/main.js"></script>
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
                <p class="text-center"><strong>Rol:</strong> <?php echo ucfirst(strtolower($usuario_rol)); ?></p>
            </div>

            <div class="nav-lateral-divider full-reset"></div>

            <div class="full-reset nav-lateral-list-menu">
                <ul class="list-unstyled">
                    <li><a href="/Biblioteca-2025/views/dashboard.php"><i class="zmdi zmdi-home"></i>&nbsp;&nbsp; Inicio</a></li>

                    <?php if ($usuario_rol === 'ADMINISTRADOR') { ?>
                        <!-- panel administrador -->
                        <li><a href="#" id="btnUsuarios"><i class="zmdi zmdi-book"></i>&nbsp;&nbsp; Registro de usuarios</a></li>
                        <li><a href="#" id="btnLibrosMenu"><i class="zmdi zmdi-calendar"></i>&nbsp;&nbsp; Registro de libros</a></li>
                        <li><a href="#" id="btnMisReservas"><i class="zmdi zmdi-trending-up"></i>&nbsp;&nbsp; Reservas</a></li>
                        <li><a href="#" id="btnPrestamos"><i class="zmdi zmdi-trending-up"></i>&nbsp;&nbsp; Prestamos</a></li>
                        <li><a href=""><i class=" zmdi zmdi-trending-up"></i>&nbsp;&nbsp; Reportes (PDF/Excel)</a></li>
                        <li><a href="#" id="btnInventario"><i class="zmdi zmdi-book"></i>&nbsp;&nbsp; Inventario</a></li>


                    <?php } elseif ($usuario_rol === 'CLIENTE') { ?>
                        <!-- panel cliente -->
                        <li><a href="#" id="btnLibrosMenu"><i class="zmdi zmdi-search"></i>&nbsp;&nbsp; Buscar libros</a></li>
                        <li><a href="#" id="btnMisReservas"><i class="zmdi zmdi-timer"></i>&nbsp;&nbsp; Mis reservas</a></li>
                        <li><a href="#" id="btnPrestamos"><i class="zmdi zmdi-search"></i>&nbsp;&nbsp; Mis prestamos</a></li>
                        <li>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario">
                                <i class="zmdi zmdi-account-circle"></i>&nbsp;&nbsp; Mis datos
                            </a>
                        </li>

                    <?php } ?>



                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalCerrarSesion">
                            <i class="zmdi zmdi-power"></i>&nbsp;&nbsp; Cerrar sesión
                        </a></li>


                </ul>
            </div>
        </div>
    </div>
    <!-- ======= CONTENIDO CENTRAL ======= -->
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
                <h1 class="all-tittles">Panel de <small><?php echo ucfirst(strtolower($usuario_rol)); ?></small></h1>
            </div>

        </div>
        <!-- tarjetas de doles -->
        <section class="full-reset text-center" style="padding: 40px 0;">
            <?php if ($usuario_rol === 'ADMINISTRADOR') { ?>
                <!--tarjetas segun administrador -->
                <article class="tile" id="btnUsuarios">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-book"></i></div>
                    <div class="tile-name all-tittles">Usuarios registrados</div>
                    <div class="tile-num full-reset"><?= $totalUsuarios ?></div>
                </article>
                <article class="tile" id="btnLibros" style="cursor:pointer;">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-book"></i></div>
                    <div class="tile-name all-tittles">Libros registrados</div>
                    <div class="tile-num full-reset"><?= $totalLibros ?></div>
                </article>
                <article class="tile" id="btnMisReservas">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-calendar"></i></div>
                    <div class="tile-name all-tittles">Reservas pendientes</div>
                    <div class="tile-num full-reset"><?= $totalReservas  ?></div>
                </article>
                <article class="tile" id="btnPrestamos">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-calendar"></i></div>
                    <div class="tile-name all-tittles">Prestamos Activos</div>
                    <div class="tile-num full-reset"><?= $totalPrestamos ?></div>
                </article>
                <article class="tile">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-trending-up"></i></div>
                    <div class="tile-name all-tittles">Reportes</div>
                    <div class="tile-num full-reset">&nbsp;</div>
                </article>
                <article class="tile" id="">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-calendar"></i></div>
                    <div class="tile-name all-tittles">Historial de préstamos</div>
                    <div class="tile-num full-reset"><?= $historialPrestamos ?></div>
                </article>
                <article class="tile" id="btnInventario">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-calendar"></i></div>
                    <div class="tile-name all-tittles">Inventario</div>
                    <div class="tile-num full-reset"></div>
                </article>
                <!-- tarejtas clientes -->
            <?php } elseif ($usuario_rol === 'CLIENTE') { ?>
                <article class="tile" id="btnLibrosMenu">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-search"></i></div>
                    <div class="tile-name all-tittles">Libros</div>
                    <div class="tile-num full-reset"><?= $totalLibros ?></div>
                </article>
                <article class="tile" id="btnMisReservas">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-timer"></i></div>
                    <div class="tile-name all-tittles">Mis reservas</div>
                    <div class="tile-num full-reset"><?= $totalReservasCliente ?></div>

                </article>
                <article class="tile" id="btnPrestamos">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-calendar"></i></div>
                    <div class="tile-name all-tittles">prestamos</div>
                    <div class="tile-num full-reset"><?= $totalPrestamosClientes ?></div>
                </article>
                <article class="tile" id="">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-calendar"></i></div>
                    <div class="tile-name all-tittles">Historial de préstamos</div>
                    <div class="tile-num full-reset"><?= $historialPrestamos ?></div>
                </article>
            <?php } ?>
        </section>
        <div id="tablaUsuariosContainer" class="contenedor-tabla"></div>
        <div id="tablaLibrosContainer" class="contenedor-tabla"></div>
        <div id="tablaLibroscliente" class="contenedor-tabla"></div>
        <div id="tablaReservasCliente" class="contenedor-tabla"></div>
        <div id="tablaPrestamosContainer" class="contenedor-tabla"></div>

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
    <?php include __DIR__ . '/modales/modalEditarLibro.php'; ?>
    <?php include __DIR__ . '/modales/RegistrarPrestamo.php'; ?>
    <?php include __DIR__ . '/modales/modalActualizarDatos.php'; ?>
    <?php include __DIR__ . '/modales/editarPrestamo.php'; ?>
    <?php include __DIR__ . '/modales/registrarReserva.php'; ?>
    <?php include __DIR__ . '/modales/editarReserva.php'; ?>
    <?php include __DIR__ . '/modales/eliminarReserva.php'; ?>









    <script src="/Biblioteca-2025/js/alertaCerrar.js"></script>
    <script src="/Biblioteca-2025/js/alertaRegistro.js"></script>
    <script src="/Biblioteca-2025/js/alertaEditar.js"></script>
    <script src="/Biblioteca-2025/js/alertaEliminar.js"></script>
    <script src="/Biblioteca-2025/js/alertaRegistroLibros.js"></script>
    <script src="/Biblioteca-2025/js/alertaEditarLibro.js"></script>
    <script src="/Biblioteca-2025/js/alertaDatosCLientes.js"></script>
    <script src="/Biblioteca-2025/js/alertaPrestamolibros.js"></script>
    <script src="/Biblioteca-2025/js/alertaEliminarLibro.js"></script>
    <script src="/Biblioteca-2025/js/alertaEditarPrestamo.js"></script>
    <script src="/Biblioteca-2025/js/alertaElimnarPrestamo.js"></script>
    <script src="/Biblioteca-2025/js/registrarReserva.js"></script>
    <script src="/Biblioteca-2025/js/editarReserva.js"></script>
    <script src="/Biblioteca-2025/js/eliminarReservas.js"></script>
    <script src="/Biblioteca-2025/js/convertirPrestamo.js"></script>







    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/tabla_usuarios.js"></script>
    <script src="../js/tabla_libros.js"></script>
    <script src="../js/librosClientes.js"></script>
    <script src="../js/tablaReservas.js"></script>
    <script src="../js/tablaPrestamos.js"></script>





</body>

</html>
<?php $baseDatos->desconectar(); ?>
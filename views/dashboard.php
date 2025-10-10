<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php?error=1");
    exit();
}

$usuario_nombre = $_SESSION['nombre'];
$usuario_rol = $_SESSION['roles']; // 'ADMINISTRADOR' o 'CLIENTE'
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Biblioteca SENAP 2025</title>

    <meta name="description" content="Biblioteca SENAP ofrece acceso a libros, recursos digitales y actividades culturales para fomentar el aprendizaje y la lectura.">
    <meta name="keywords" content="biblioteca, educación, lectura, libros digitales, cultura">
    <meta name="author" content="Cristian Villa y Jhoan Morales">

    <link rel="Shortcut Icon" type="image/x-icon" href="/Biblioteca-2025/assets/icons/book.ico" />

    <!-- ===== CSS ===== -->
    <link rel="stylesheet" href="/Biblioteca-2025/css/sweet-alert.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/normalize.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="/Biblioteca-2025/css/style.css">

    <!-- ===== JS ===== -->
    <script src="/Biblioteca-2025/js/sweet-alert.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>
        window.jQuery || document.write('<script src="/Biblioteca-2025/js/jquery-1.11.2.min.js"><\/script>')
    </script>
    <script src="/Biblioteca-2025/js/modernizr.js"></script>
    <script src="/Biblioteca-2025/js/bootstrap.min.js"></script>
    <script src="/Biblioteca-2025/js/jquery.mCustomScrollbar.concat.min.js"></script>
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
                    <img src="/Biblioteca-2025/assets/img/logo.png" alt="Biblioteca" class="img-responsive center-box" style="width:55%;">
                </figure>
                <p class="text-center" style="padding-top: 15px;">Biblioteca SENAP</p>
                <p class="text-center"><strong>Rol:</strong> <?php echo ucfirst(strtolower($usuario_rol)); ?></p>
            </div>

            <div class="nav-lateral-divider full-reset"></div>

            <div class="full-reset nav-lateral-list-menu">
                <ul class="list-unstyled">
                    <li><a href="/Biblioteca-2025/views/dashboard.php"><i class="zmdi zmdi-home"></i>&nbsp;&nbsp; Inicio</a></li>

                    <?php if ($usuario_rol === 'ADMINISTRADOR') { ?>
                        <!-- SOLO ADMIN -->
                        <li><a href="/Biblioteca-2025/views/book.php"><i class="zmdi zmdi-book"></i>&nbsp;&nbsp; Registro de usuarios:</a></li>
                        <li><a href="/Biblioteca-2025/views/loan.php"><i class="zmdi zmdi-calendar"></i>&nbsp;&nbsp; Registro de nuevos clientes.</a></li>
                        <li><a href="/Biblioteca-2025/views/report.php"><i class="zmdi zmdi-trending-up"></i>&nbsp;&nbsp; Inventario</a></li>
                        <li><a href="/Biblioteca-2025/views/report.php"><i class="zmdi zmdi-trending-up"></i>&nbsp;&nbsp; Reportes</a></li>

                    <?php } elseif ($usuario_rol === 'CLIENTE') { ?>
                        <!-- SOLO CLIENTE -->
                        <li><a href="/Biblioteca-2025/views/searchbook.php"><i class="zmdi zmdi-search"></i>&nbsp;&nbsp; Buscar libros</a></li>
                        <li><a href="/Biblioteca-2025/views/myreservations.php"><i class="zmdi zmdi-timer"></i>&nbsp;&nbsp; Mis reservas</a></li>
                    <?php } ?>

                    <li><a href="/Biblioteca-2025/logout.php"><i class="zmdi zmdi-power"></i>&nbsp;&nbsp; Cerrar sesión</a></li>
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

        <!-- TARJETAS SEGÚN ROL -->
        <section class="full-reset text-center" style="padding: 40px 0;">
            <?php if ($usuario_rol === 'ADMINISTRADOR') { ?>
                <!-- TARJETAS ADMIN -->
                <article class="tile">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-book"></i></div>
                    <div class="tile-name all-tittles">Libros registrados</div>
                    <div class="tile-num full-reset">77</div>
                </article>
                <article class="tile">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-calendar"></i></div>
                    <div class="tile-name all-tittles">Préstamos activos</div>
                    <div class="tile-num full-reset">7</div>
                </article>
                <article class="tile">
                    <div class="tile-icon full-reset"><i class="zmdi zmdi-trending-up"></i></div>
                    <div class="tile-name all-tittles">Reportes</div>
                    <div class="tile-num full-reset">&nbsp;</div>
                </article>

            <?php } elseif ($usuario_rol === 'CLIENTE') { ?>
                <!-- TARJETAS CLIENTE -->
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
        <footer>
            © 2025 Biblioteca-2025 | Desarrollado por Cristian Villa y Jhoan Morales
        </footer>

    </div>
</body>

</html>
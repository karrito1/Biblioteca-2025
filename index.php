<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<!-- 
* Copyright 2018 Carlos Eduardo Alfaro Orellana
-->
>>>>>>> b74019c (Se realizaron cambios visuales en en login y index 10-10-2025)
>>>>>>> e33ffda (Se realizaron cambios visuales en en login y index 10-10-2025)
<!DOCTYPE html>
<html lang="es">

<head>
<<<<<<< HEAD
=======
<<<<<<< HEAD
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f1f1f1;
        }

        .login-container {
            max-width: 380px;
            margin: 80px auto;
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        h5 {
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h5 class="text-center">INICIO DE SESIÓN</h5>

        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <div class="alert alert-danger text-center" role="alert">
                Número de documento o contraseña incorrectos.
            </div>
        <?php endif; ?>

        <!-- IMPORTANTE: acción apunta directo al controller -->
        <form method="POST" action="controller/login_usuarios.php">

            <!-- Documento -->
            <div class="mb-3">
                <label class="form-label" for="numeroDocumento">Número Documento</label>
                <input type="number" class="form-control" name="numeroDocumento" required />
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input type="password" class="form-control" name="passwordd" required />
            </div>

            <!-- Botón -->
            <button type="submit" class="btn btn-primary" name="btniniciarsesion" value="ok">
                INICIAR SESIÓN
            </button>
        </form>
    </div>

=======
>>>>>>> e33ffda (Se realizaron cambios visuales en en login y index 10-10-2025)
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Biblioteca SENAP 2025</title>

    <meta name="description" content="Biblioteca SENAP ofrece acceso a libros, recursos digitales y actividades culturales para fomentar el aprendizaje y la lectura." />
    <meta name="keywords" content="biblioteca, educación, lectura, libros digitales, cultura" />
    <meta name="author" content="Cristian Villa y Jhoan Morales" />

<<<<<<< HEAD
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/068a4d5189.js" crossorigin="anonymous"></script>

    <!-- Tu CSS -->
    <link rel="stylesheet" href="/css/estilos.css" />
</head>

<body>

    <!-- 🔹 Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">📚 Biblioteca SENAP 2025</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#servicios">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link" href="#nosotros">Nosotros</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- 🔹 Hero -->
    <section class="hero text-center py-5 bg-light">
        <h1>Bienvenido a la Biblioteca SENAP 2025</h1>
        <p>Accede a miles de libros, recursos digitales y programas culturales desde cualquier lugar.</p>
        <a href="#" class="btn btn-primary btn-lg mt-3" data-bs-toggle="modal" data-bs-target="#loginModal">
            Iniciar Sesión
        </a>
    </section>

    <!-- 🔹 Servicios -->
    <section id="servicios" class="servicios container my-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-success">Nuestros Servicios</h2>
            <p class="text-muted">Descubre todo lo que ofrecemos para potenciar tu aprendizaje.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="servicio-card text-center p-4 shadow-sm rounded-4">
                    <i class="fa-solid fa-book fa-2x mb-3"></i>
                    <h4>Catálogo de Libros</h4>
                    <p>Consulta nuestra colección completa y descubre nuevos títulos cada mes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="servicio-card text-center p-4 shadow-sm rounded-4">
                    <i class="fa-solid fa-calendar-check fa-2x mb-3"></i>
                    <h4>Reservas Online</h4>
                    <p>Reserva tus libros fácilmente desde cualquier dispositivo.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="servicio-card text-center p-4 shadow-sm rounded-4">
                    <i class="fa-solid fa-users fa-2x mb-3"></i>
                    <h4>Actividades Culturales</h4>
                    <p>Participa en talleres, clubes de lectura y eventos educativos.</p>
                </div>
            </div>
        </div>
    </section>
    <section id="nosotros" class="container my-5">
        <div class="text-center">
            <h2 class="fw-bold text-success">Sobre Nosotros</h2>
            <p class="text-muted">
                Somos una biblioteca moderna enfocada en la innovación educativa y la inclusión digital.
                Nuestro objetivo es fomentar el amor por la lectura en todas las generaciones.
            </p>
        </div>
    </section>


    <footer class="text-center py-3 bg-primary text-white">
        <p>© 2025 Biblioteca SENAP | Desarrollado por Cristian Villa y Jhoan Morales</p>
    </footer>

    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
        <div class="alert alert-danger text-center" role="alert">
            Email o contraseña incorrectos.
        </div>
    <?php endif; ?>
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="loginModalLabel">Inicio de Sesión</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <form method="POST" action="./controllers/logicalogin.php">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">Recordarme</label>
                            </div>
                            <a href="#" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                    </div>

                    <div class="modal-footer">
                        <p class="m-0">¿No tienes cuenta?
                            <a href="#" class="text-primary text-decoration-none">Regístrate</a>
                        </p>
                    </div>
                </form>
            </div>
=======
    <!-- Bootstrap y otros estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/068a4d5189.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/sweet-alert.css">
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="css/style.css">

    <!-- Scripts -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>
        window.jQuery || document.write('<script src="js/jquery-1.11.2.min.js"><\/script>')
    </script>
    <script src="js/modernizr.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/main.js"></script>
</head>

<body>
    <div class="login-container full-cover-background">
        <div class="form-container">
            <p class="text-center" style="margin-top: 17px;">
                <i class="zmdi zmdi-account-circle zmdi-hc-5x"></i>
            </p>
            <h4 class="text-center all-tittles" style="margin-bottom: 30px;">Inicia sesión con tu cuenta</h4>

            <!-- Mensaje de error si hay problema con el login -->
            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                <div class="alert alert-danger text-center" role="alert">
                    Email o contraseña incorrectos.
                </div>
            <?php endif; ?>

            <!-- Formulario de inicio de sesión -->
            <form method="POST" action="./controllers/logicalogin.php">
                <div class="group-material-login">
                    <input type="email" class="material-login-control" required maxlength="70" name="email" id="email">
                    <span class="highlight-login"></span>
                    <span class="bar-login"></span>
                    <label><i class="zmdi zmdi-account"></i> &nbsp; Correo electrónico</label>
                </div>
                <br>
                <div class="group-material-login">
                    <input type="password" class="material-login-control" required maxlength="70" name="password" id="password">
                    <span class="highlight-login"></span>
                    <span class="bar-login"></span>
                    <label><i class="zmdi zmdi-lock"></i> &nbsp; Contraseña</label>
                </div>
                <button class="btn-login" type="submit">
                    Ingresar al sistema &nbsp; <i class="zmdi zmdi-arrow-right"></i>
                </button>
            </form>
>>>>>>> e33ffda (Se realizaron cambios visuales en en login y index 10-10-2025)
        </div>
    </div>

    <!-- Bootstrap JS -->
<<<<<<< HEAD
=======
>>>>>>> b74019c (Se realizaron cambios visuales en en login y index 10-10-2025)
>>>>>>> e33ffda (Se realizaron cambios visuales en en login y index 10-10-2025)
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
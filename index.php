<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Biblioteca SENAP 2025</title>

    <meta name="description" content="Biblioteca SENAP ofrece acceso a libros, recursos digitales y actividades culturales para fomentar el aprendizaje y la lectura." />
    <meta name="keywords" content="biblioteca, educación, lectura, libros digitales, cultura" />
    <meta name="author" content="Cristian Villa y Jhoan Morales" />

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
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
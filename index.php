<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca SENAP 2025 - Inicio de sesión</title>
    <link rel="shortcut icon" href="assets/icons/book.ico" type="image/x-icon">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome y ZMDI -->
    <script src="https://kit.fontawesome.com/068a4d5189.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">

    <!-- SweetAlert y estilos propios -->
    <link rel="stylesheet" href="css/sweet-alert.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="css/style.css">

    <!-- jQuery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>
        window.jQuery || document.write('<script src="js/jquery-1.11.2.min.js"><\/script>')
    </script>
    <script src="js/modernizr.js"></script>
</head>

<body>
    <div class="login-container full-cover-background d-flex justify-content-center align-items-center vh-100">
        <div class="form-container p-4 shadow rounded" style="background-color: rgba(32, 29, 29, 0.9); max-width: 400px; width: 100%;">
            <div class="text-center mb-4">
                <i class="zmdi zmdi-account-circle zmdi-hc-5x"></i>
                <h4 class="mt-3 all-tittles">Inicia sesión con tu cuenta</h4>
            </div>

            <!-- Mensaje de error -->
            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                <div class="alert alert-danger text-center" role="alert">
                    Email o contraseña incorrectos.
                </div>
            <?php endif; ?>
            <form method="POST" action="./controllers/logicalogin.php">
                <div class="group-material-login">
                    <input type="email" class="material-login-control" name="email" required maxlength="70">
                    <span class="highlight-login"></span>
                    <span class="bar-login"></span>
                    <label><i class="zmdi zmdi-account"></i> &nbsp; correo</label>
                </div>
                <br>
                <div class="group-material-login">
                    <input type="password" class="material-login-control" name="password" required maxlength="70">
                    <span class="highlight-login"></span>
                    <span class="bar-login"></span>
                    <label><i class="zmdi zmdi-lock"></i> &nbsp; Contraseña</label>
                </div>
                <button class="btn-login" type="submit">
                    Ingresar al sistema &nbsp; <i class="zmdi zmdi-arrow-right"></i>
                </button>

            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/sweet-alert.min.js"></script>
</body>

</html>
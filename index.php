<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca SENAP 2025 - Inicio de sesion</title>
    <link rel="shortcut icon" href="assets/icons/book.ico" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/068a4d5189.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.20/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/sweet-alert.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.5.min.js"></script>
</head>

<body>
    <div class="login-container full-cover-background d-flex justify-content-center align-items-center vh-100">
        <div class="form-container p-5 shadow-lg rounded"
            style="background-color: rgba(32, 29, 29, 0.95); max-width: 420px; width: 100%;">

            <div class="text-center mb-4">
                <i class="zmdi zmdi-account-circle zmdi-hc-5x text-light"></i>
                <h4 class="mt-3 text-light all-tittles">Inicia sesion con tu cuenta</h4>
            </div>

            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                <div class="alert alert-danger text-center" role="alert">
                    Email o contrasena incorrectos.
                </div>
            <?php endif; ?>

            <form method="POST" action="./controllers/logicalogin.php" class="mb-3">
                <div class="group-material-login mb-4">
                    <input type="email" class="material-login-control form-control form-control-lg" name="email"
                        required maxlength="70">
                    <label><i class="zmdi zmdi-account"></i> &nbsp; Correo</label>
                </div>

                <div class="group-material-login mb-4">
                    <input type="password" class="material-login-control form-control form-control-lg" name="password"
                        required maxlength="70">
                    <label><i class="zmdi zmdi-lock"></i> &nbsp; Contrasena</label>
                </div>

                <div class="d-grid gap-3">
                    <button class="btn btn-primary btn-lg" type="submit">
                        Ingresar &nbsp; <i class="zmdi zmdi-arrow-right"></i>
                    </button>
                    <button class="btn btn-success btn-lg" type="button" data-bs-toggle="modal"
                        data-bs-target="#registroModal">
                        Crear cuenta nueva &nbsp; <i class="zmdi zmdi-account-add"></i>
                    </button>
                </div>
            </form>

            <div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="background-color: rgba(32, 29, 29, 0.95); color: white;">
                        <div class="modal-header border-0">
                            <h5 class="modal-title" id="registroModalLabel">
                                <i class="zmdi zmdi-account-add"></i> &nbsp; Crear una cuenta
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <form id="formRegistro" method="POST" action="./controllers/indexRegistro.php">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre completo</label>
                                    <input type="text" class="form-control form-control-lg" id="nombre" name="nombre" required maxlength="70">
                                </div>

                                <div class="mb-3">
                                    <label for="emailRegistro" class="form-label">Correo electronico</label>
                                    <input type="email" class="form-control form-control-lg" id="emailRegistro" name="email" required maxlength="70">
                                </div>

                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Telefono</label>
                                    <input type="text" class="form-control form-control-lg" id="telefono" name="telefono" maxlength="20">
                                </div>

                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Direccion</label>
                                    <textarea class="form-control form-control-lg" id="direccion" name="direccion" rows="2" maxlength="255"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="passwordRegistro" class="form-label">Contrasena</label>
                                    <input type="password" class="form-control form-control-lg" id="passwordRegistro" name="passwordd" required maxlength="70">
                                </div>
                            </div>

                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success">Registrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/main.js"></script>

    <?php if (isset($_GET["registro"])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                <?php if ($_GET["registro"] === "ok"): ?>
                    Swal.fire({
                        icon: "success",
                        title: "Registro exitoso",
                        text: "Tu cuenta se creó correctamente.",
                        background: "#1e1e1e",
                        color: "#fff",
                        confirmButtonColor: "#3085d6"
                    });

                <?php elseif ($_GET["registro"] === "duplicado"): ?>
                    Swal.fire({
                        icon: "warning",
                        title: "Correo ya registrado",
                        html: `
                        <p style="font-size: 1rem; color: #ddd;">
                            El correo ingresado ya está en uso.
                        </p>
                        <p style="color: #aaa; margin-top: 8px;">
                            ¿Ya tienes cuenta?
                          
                        </p>
                    `,
                        background: "linear-gradient(145deg, #1c1c1c, #252525)",
                        color: "#fff",
                        confirmButtonText: "Entendido",
                        confirmButtonColor: "#00bcd4"
                    });

                <?php elseif ($_GET["registro"] === "faltan"): ?>
                    Swal.fire({
                        icon: "warning",
                        title: "Campos incompletos",
                        text: "Por favor completa todos los campos.",
                        background: "#1e1e1e",
                        color: "#fff",
                        confirmButtonColor: "#f0ad4e"
                    });

                <?php elseif ($_GET["registro"] === "error"): ?>
                    Swal.fire({
                        icon: "error",
                        title: "Error al registrar",
                        text: "Ocurrió un problema en la base de datos.",
                        background: "#1e1e1e",
                        color: "#fff",
                        confirmButtonColor: "#d33"
                    });
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>



</body>

</html>
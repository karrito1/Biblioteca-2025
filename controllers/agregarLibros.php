<?php
require_once '../models/MySQL.PHP';

$mensaje = '';
$tipoMensaje = '';

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $titulo = $_POST['titulo'] ?? '';
    $autor = $_POST['autor'] ?? '';
    $isbn = $_POST['isbn'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $cantidad = $_POST['cantidad'] ?? 0;

    // Validar que los campos requeridos no estén vacíos
    if (empty($titulo) || empty($autor) || empty($isbn)) {
        $mensaje = "Por favor, complete todos los campos requeridos.";
        $tipoMensaje = "danger";
    } else {
        try {
            $db = new MySQL();
            $db->conectar();

            // Crear la consulta SQL para insertar el libro
            $consulta = "INSERT INTO libro (titulo, autor, isbn, categoria, cantidad, disponibilidad) 
                        VALUES ('$titulo', '$autor', '$isbn', '$categoria', $cantidad, 'disponible')";

            // Ejecutar la consulta
            $resultado = $db->efectuarConsulta($consulta);

            if ($resultado) {
                $mensaje = "Libro agregado exitosamente.";
                $tipoMensaje = "success";
                // Limpiar el formulario después de una inserción exitosa
                $_POST = array();
            } else {
                $mensaje = "Error al agregar el libro: " . $db->getError();
                $tipoMensaje = "danger";
            }

            $db->desconectar();
        } catch (Exception $e) {
            $mensaje = "Error de conexión: " . $e->getMessage();
            $tipoMensaje = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agregar Libro - Biblioteca</title>
    
    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6.4.2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="../stylo.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php">
                <i class="fas fa-book"></i> Biblioteca
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">
                            <i class="fas fa-book-medical"></i> Agregar Nuevo Libro
                        </h2>

                        <?php if (!empty($mensaje)): ?>
                            <div class="alert alert-<?php echo $tipoMensaje; ?> alert-dismissible fade show" role="alert">
                                <?php echo $mensaje; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" 
                                       value="<?php echo $_POST['titulo'] ?? ''; ?>" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese el título del libro.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="autor" class="form-label">Autor *</label>
                                <input type="text" class="form-control" id="autor" name="autor" 
                                       value="<?php echo $_POST['autor'] ?? ''; ?>" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese el autor del libro.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="isbn" class="form-label">ISBN *</label>
                                <input type="text" class="form-control" id="isbn" name="isbn" 
                                       value="<?php echo $_POST['isbn'] ?? ''; ?>" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese el ISBN del libro.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoría</label>
                                <input type="text" class="form-control" id="categoria" name="categoria" 
                                       value="<?php echo $_POST['categoria'] ?? ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" 
                                       value="<?php echo $_POST['cantidad'] ?? '1'; ?>" min="0">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Guardar Libro
                                </button>
                                <a href="../index.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Volver
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FontAwesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    
    <script>
    // Activar validación de formularios de Bootstrap
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
    </script>
</body>
</html>

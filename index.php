<?php
require_once 'models/MySQL.PHP';
$estado = '';
try {
    $db = new MySQL();
    $db->conectar();
    $estado = '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Conexión exitosa</span>';
    $db->desconectar();
} catch (Exception $e) {
    $estado = '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Error de conexión</span>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Biblioteca - Sistema de Gestión</title>
    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6.4.2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Animate.css para animaciones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="stylo.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="fas fa-book"></i> Biblioteca</a>
        </div>
    </nav>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-body text-center">
                        <h1 class="display-5 fw-bold mb-2"><i class="fas fa-book-reader"></i> Bienvenido a la Biblioteca</h1>
                        <p class="lead mb-3">Gestión de libros y usuarios.</p>
                        <div class="alert alert-light border d-inline-block px-4 py-2 mb-0">
                            Estado de la base de datos: <?php echo $estado; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center g-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <i class="fas fa-plus-circle fa-2x text-success mb-2"></i>
                        <h5 class="card-title">Agregar Libro</h5>
                        <a href="controllers/agregarLibros.php" class="btn btn-success w-100 mt-auto">Agregar</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <i class="fas fa-user-plus fa-2x text-info mb-2"></i>
                        <h5 class="card-title">Agregar Usuario</h5>
                        <a href="views/crearUsuarios.php" class="btn btn-info w-100 mt-auto">Agregar</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <i class="fas fa-pen fa-2x text-warning mb-2"></i>
                        <h5 class="card-title">Editar Libros</h5>
                        <a href="controllers/editarLibros.php" class="btn btn-warning w-100 mt-auto text-white">Editar</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card h-100 text-center shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <i class="fas fa-trash fa-2x text-danger mb-2"></i>
                        <h5 class="card-title">Eliminar Libros</h5>
                        <a href="controllers/eliminarLibros.php" class="btn btn-danger w-100 mt-auto">Eliminar</a>
                    </div>
                </div>
            </div>
        </div>
        <footer class="text-center mt-5 mb-3 text-muted">
            <small>&copy; <?php echo date('Y'); ?> Biblioteca SENAP</small>
        </footer>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FontAwesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</body>
</html>
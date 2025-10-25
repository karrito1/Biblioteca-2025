<?php
session_start();
require_once("../models/MySQL.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php?error=1");
    exit();
}

$usuario_nombre = $_SESSION['email'];
$usuario_rol = $_SESSION['roles'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Simplificado</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Bienvenido, <?php echo htmlspecialchars($usuario_nombre); ?></h1>
        <p>Rol: <?php echo htmlspecialchars($usuario_rol); ?></p>

        <div class="mb-4">
            <button id="btnTestLibros" class="btn btn-primary">Cargar Libros</button>
        </div>

        <div id="tablaContainer"></div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
    $(document).ready(function() {
        console.log('Página cargada');

        $('#btnTestLibros').on('click', function() {
            console.log('Botón clickeado');
            
            $('#tablaContainer').html('<div class="text-center">Cargando...</div>');

            $.get('../views/tabla_libros.php')
                .done(function(response) {
                    console.log('Respuesta recibida');
                    $('#tablaContainer').html(response);
                    
                    if ($.fn.DataTable) {
                        try {
                            $('#tablalibros').DataTable({
                                pageLength: 10,
                                language: {
                                    url: '../js/es-ES.json'
                                }
                            });
                        } catch (e) {
                            console.error('Error al inicializar DataTable:', e);
                        }
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('Error:', status, error);
                    $('#tablaContainer').html(
                        '<div class="alert alert-danger">Error al cargar los datos</div>'
                    );
                });
        });
    });
    </script>
</body>
</html>
<?php
require_once("../models/MySQL.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $baseDatos = new MySQL();
  $conexion = $baseDatos->conectar();

  $usuario_id = intval($_POST['usuario_id']);
  $libro_id = intval($_POST['libro_id']);
  $fecha_prestamo = $_POST['fecha_prestamo'];
  $fecha_devolucion = $_POST['fecha_devolucion'];
  $estado = $_POST['estado'];

  // Insertar el prestamo
  $query = "INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo, fecha_devolucion, estado)
              VALUES ('$usuario_id', '$libro_id', '$fecha_prestamo', '$fecha_devolucion', '$estado')";

  if (mysqli_query($conexion, $query)) {
    // Cambiar estado del libro a "NO DISPONIBLE"
    $updateLibro = "UPDATE libros SET disponibilidad = 'NO DISPONIBLE' WHERE id = '$libro_id'";
    mysqli_query($conexion, $updateLibro);

    echo "<script>
                alert('✅ Préstamo registrado exitosamente.');
                window.location.href='../views/prestamos.php';
              </script>";
  } else {
    echo "<script>
                alert('❌ Error al registrar el préstamo: " . mysqli_error($conexion) . "');
                window.history.back();
              </script>";
  }

  $baseDatos->desconectar();
}

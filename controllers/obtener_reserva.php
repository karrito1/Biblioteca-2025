<?php
// Habilitar errores para depuracion
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once("../models/MySQL.php");

header('Content-Type: application/json');

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Validar sesion
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['roles'])) {
    echo json_encode(['error' => 'Sesion no valida']);
    exit;
}

// Solo administradores pueden editar
if (strtoupper(trim($_SESSION['roles'])) !== "ADMINISTRADOR") {
    echo json_encode(['error' => 'No tiene permisos para editar reservas']);
    exit;
}

// Obtener ID de la reserva
$reserva_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($reserva_id <= 0) {
    echo json_encode(['error' => 'ID no valido']);
    exit;
}

// Consulta para obtener datos de la reserva
$query = "SELECT 
            reservas.id,
            reservas.usuario_id,
            reservas.libro_id,
            DATE(reservas.fecha_reserva) as fecha_reserva,
            reservas.estado,
            usuarios.nombre as usuario_nombre,
            libros.titulo as libro_titulo,
            libros.isbn
          FROM reservas
          LEFT JOIN usuarios ON reservas.usuario_id = usuarios.id
          LEFT JOIN libros ON reservas.libro_id = libros.id
          WHERE reservas.id = $reserva_id";

$result = mysqli_query($conexion, $query);

if (!$result) {
    echo json_encode(['error' => 'Error en la consulta: ' . mysqli_error($conexion)]);
    $baseDatos->desconectar();
    exit;
}

$reserva = mysqli_fetch_assoc($result);

if ($reserva) {
    echo json_encode($reserva);
} else {
    echo json_encode(['error' => 'Reserva no encontrada']);
}

// Cerrar conexion
$baseDatos->desconectar();
?>
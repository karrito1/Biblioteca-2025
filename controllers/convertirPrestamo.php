<?php
session_start();
require_once("../models/MySQL.php");

header('Content-Type: application/json');

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Validar sesion y rol
if (!isset($_SESSION['usuario_id']) || strtoupper(trim($_SESSION['roles'])) !== 'ADMINISTRADOR') {
    echo json_encode(['success' => false, 'message' => 'No tiene permisos']);
    exit;
}

// Validar ID de reserva
$reserva_id = isset($_POST['reserva_id']) ? (int)$_POST['reserva_id'] : 0;
if ($reserva_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de reserva no valido']);
    exit;
}

// Obtener datos de la reserva aprobada
$sql = "SELECT usuario_id, libro_id, fecha_reserva FROM reservas WHERE id=$reserva_id AND estado='aprobada'";
$result = mysqli_query($conexion, $sql);
$reserva = mysqli_fetch_assoc($result);

if (!$reserva) {
    echo json_encode(['success' => false, 'message' => 'Reserva no encontrada o no aprobada']);
    exit;
}

$usuario_id = $reserva['usuario_id'];
$libro_id = $reserva['libro_id'];
$fecha_prestamo = date('Y-m-d'); // hoy
$fecha_devolucion = date('Y-m-d', strtotime('+7 days')); // 7 dias despues
$estado = 'activo';

// Insertar en tabla prestamos
$sql_insert = "INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo, fecha_devolucion, estado) 
               VALUES ($usuario_id, $libro_id, '$fecha_prestamo', '$fecha_devolucion', '$estado')";
if (mysqli_query($conexion, $sql_insert)) {
    // Actualizar estado de la reserva a 'convertida'
    mysqli_query($conexion, "UPDATE reservas SET estado='convertida' WHERE id=$reserva_id");
    echo json_encode(['success' => true, 'message' => 'Reserva convertida en prestamo']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al crear el prestamo']);
}

$baseDatos->desconectar();

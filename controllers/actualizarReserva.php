<?php
session_start();
require_once("../models/MySQL.php");

// Cabecera para devolver JSON
header('Content-Type: application/json');

// Crear conexion
$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Validar sesion
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['roles'])) {
    echo json_encode(['success' => false, 'message' => 'Sesion no valida']);
    exit;
}

// Solo administradores pueden actualizar
if (strtoupper(trim($_SESSION['roles'])) !== "ADMINISTRADOR") {
    echo json_encode(['success' => false, 'message' => 'No tiene permisos']);
    exit;
}

// Obtener datos del POST
$reserva_id = isset($_POST['reserva_id']) ? (int) $_POST['reserva_id'] : 0;
$fecha_reserva = isset($_POST['fecha_reserva']) ? mysqli_real_escape_string($conexion, $_POST['fecha_reserva']) : '';
$estado = isset($_POST['estado']) ? mysqli_real_escape_string($conexion, strtolower($_POST['estado'])) : '';

// Validar datos
if ($reserva_id <= 0 || empty($fecha_reserva) || empty($estado)) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

// Validar estado
$estados_validos = ['pendiente','aprobada','rechazada'];
if (!in_array($estado, $estados_validos)) {
    echo json_encode(['success' => false, 'message' => 'Estado no valido']);
    exit;
}

// Actualizar reserva en la base de datos
$sql = "UPDATE reservas SET fecha_reserva='$fecha_reserva', estado='$estado' WHERE id=$reserva_id";

if (mysqli_query($conexion, $sql)) {
    echo json_encode(['success' => true, 'message' => 'Reserva actualizada exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar la reserva']);
}

// Cerrar conexion
$baseDatos->desconectar();
?>

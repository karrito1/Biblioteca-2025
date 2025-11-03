<?php
session_start();
require_once("../models/MySQL.php");
header('Content-Type: application/json');

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Validar sesion
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['roles'])) {
    echo json_encode(['success' => false, 'message' => 'Sesion no valida']);
    exit;
}

// Solo admin
if (strtoupper(trim($_SESSION['roles'])) !== 'ADMINISTRADOR') {
    echo json_encode(['success' => false, 'message' => 'No tiene permisos']);
    exit;
}

// Validar ID
$reserva_id = isset($_POST['reserva_id']) ? (int)$_POST['reserva_id'] : 0;
if ($reserva_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID no valido']);
    exit;
}

// Eliminar reserva
$sql = "DELETE FROM reservas WHERE id=$reserva_id";
if (mysqli_query($conexion, $sql)) {
    if (mysqli_affected_rows($conexion) > 0) {
        echo json_encode(['success' => true, 'message' => 'Reserva eliminada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontro la reserva con ese ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta']);
}

$baseDatos->desconectar();

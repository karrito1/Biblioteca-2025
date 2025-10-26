<?php
session_start();
require_once '../models/MySQL.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['usuario_id'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Acceso no autorizado']);
    exit;
}

$bd = new MySQL();
$stmt = $bd->preparar("UPDATE prestamos SET estado = 'devuelto' WHERE id = ? AND estado = 'activo'");
$stmt->bind_param("i", (int)$_POST['prestamo_id']);

echo json_encode([
    'exito' => $stmt->execute() && $stmt->affected_rows > 0,
    'mensaje' => $stmt->execute() && $stmt->affected_rows > 0 ? 'Libro devuelto exitosamente' : 'No se pudo devolver el libro'
]);

$bd->desconectar();
?>
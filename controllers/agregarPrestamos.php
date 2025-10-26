<?php
session_start();
require_once '../models/MySQL.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['usuario_id'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Acceso no autorizado']);
    exit;
}

$usuario_id = (int)$_POST['usuario_id'];
$libro_id = (int)$_POST['libro_id'];
$fecha_devolucion = $_POST['fecha_devolucion'];

$bd = new MySQL();
$conexion = $bd->conectar();

$stmt = $bd->preparar("SELECT cantidad FROM libros WHERE id = ? AND disponibilidad = 'disponible'");
$stmt->bind_param("i", $libro_id);
$stmt->execute();

if ($stmt->get_result()->num_rows === 0) {
    echo json_encode(['exito' => false, 'mensaje' => 'Libro no disponible']);
    exit;
}

$stmt = $bd->preparar("INSERT INTO prestamos (usuario_id, libro_id, fecha_devolucion) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $usuario_id, $libro_id, $fecha_devolucion);

$result = $stmt->execute();

echo json_encode([
    'exito' => $result,
    'mensaje' => $result ? 'prestamo registrado exitosamente' : 'error al registrar prestamo'
]);

$bd->desconectar();
?>
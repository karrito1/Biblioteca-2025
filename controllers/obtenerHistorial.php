<?php
require_once __DIR__ . "/../models/MySQL.php";

session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['roles'] !== 'ADMINISTRADOR') {
    http_response_code(403);
    echo json_encode(['error' => 'acceso denegado']);
    exit();
}

$baseDatos = new MySQL();
$baseDatos->conectar();

$consulta = "SELECT p.id, u.nombre as usuario, l.titulo as libro, p.fecha_prestamo, p.fecha_devolucion, p.estado 
             FROM prestamos p 
             JOIN usuarios u ON p.usuario_id = u.id 
             JOIN libros l ON p.libro_id = l.id 
             ORDER BY p.fecha_prestamo DESC 
             LIMIT 100";

$resultado = $baseDatos->efectuarConsulta($consulta);
$historial = [];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $historial[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($historial);

$baseDatos->desconectar();
?>

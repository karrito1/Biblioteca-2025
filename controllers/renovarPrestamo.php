<?php
session_start();
require_once '../models/MySQL.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario_id'])) {
    $prestamo_id = (int)$_POST['prestamo_id'];
    $nueva_fecha = $_POST['nueva_fecha_devolucion'];
    
    $bd = new MySQL();
    $conexion = $bd->conectar();
    
    // renovar prestamo
    $consulta = "UPDATE prestamos SET fecha_devolucion = ? WHERE id = ? AND estado = 'activo'";
    $stmt = $bd->preparar($consulta);
    $stmt->bind_param("si", $nueva_fecha, $prestamo_id);
    
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['exito' => true, 'mensaje' => 'prestamo renovado exitosamente']);
    } else {
        echo json_encode(['exito' => false, 'mensaje' => 'no se pudo renovar el prestamo']);
    }
    
    $bd->desconectar();
} else {
    echo json_encode(['exito' => false, 'mensaje' => 'Acceso no autorizado']);
}
?>
<?php
session_start();
require_once '../models/MySQL.php';

header('Content-Type: application/json');

if (isset($_SESSION['usuario_id'])) {
    $bd = new MySQL();
    $conexion = $bd->conectar();
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'todos';
    
    switch ($tipo) {
        case 'usuarios_disponibles':
            $consulta = "SELECT id, nombre, email FROM usuarios WHERE estado = 'activo' AND Roles = 'CLIENTE'";
            break;
            
        case 'libros_disponibles':
            $consulta = "SELECT id, titulo, autor FROM libros WHERE disponibilidad = 'disponible'";
            break;
            
        case 'vencidos':
            $consulta = "SELECT p.*, u.nombre as usuario_nombre, l.titulo as libro_titulo 
                        FROM prestamos p 
                        INNER JOIN usuarios u ON p.usuario_id = u.id 
                        INNER JOIN libros l ON p.libro_id = l.id 
                        WHERE p.estado = 'activo' AND p.fecha_devolucion < CURDATE()";
            break;
            
        default:
            $consulta = "SELECT p.*, u.nombre as usuario_nombre, l.titulo as libro_titulo 
                        FROM prestamos p 
                        INNER JOIN usuarios u ON p.usuario_id = u.id 
                        INNER JOIN libros l ON p.libro_id = l.id 
                        ORDER BY p.fecha_prestamo DESC";
            break;
    }
    
    $resultado = $bd->efectuarConsulta($consulta);
    $datos = [];
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $datos[] = $fila;
    }
    
    echo json_encode(['exito' => true, 'datos' => $datos]);
    $bd->desconectar();
} else {
    echo json_encode(['exito' => false, 'mensaje' => 'acceso no autorizado']);
}
?>
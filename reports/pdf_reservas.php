<?php
session_start();
require_once("../models/MySQL.php");
require '../vendor/autoload.php';

use Dompdf\Dompdf;

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Validar sesión
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['roles'])) {
    die("Error: sesión no válida.");
}

$usuario_id = (int) $_SESSION['usuario_id'];
$rol = strtoupper(trim($_SESSION['roles']));

// Consulta según rol
if ($rol === "CLIENTE") {
    $query = "SELECT 
                reservas.fecha_reserva,
                reservas.estado,
                usuarios.nombre,
                libros.titulo,
                libros.isbn
              FROM reservas
              LEFT JOIN usuarios ON reservas.usuario_id = usuarios.id
              LEFT JOIN libros ON reservas.libro_id = libros.id
              WHERE reservas.usuario_id = $usuario_id
              ORDER BY reservas.fecha_reserva DESC";
} elseif ($rol === "ADMINISTRADOR") {
    $query = "SELECT 
                reservas.fecha_reserva,
                reservas.estado,
                usuarios.nombre,
                libros.titulo,
                libros.isbn
              FROM reservas
              LEFT JOIN usuarios ON reservas.usuario_id = usuarios.id
              LEFT JOIN libros ON reservas.libro_id = libros.id
              ORDER BY reservas.fecha_reserva DESC";
} else {
    die("Error: rol no válido o no definido.");
}

$result = $baseDatos->efectuarConsulta($query);

// Generar HTML
$html = '<h1>Reservas</h1>';
$html .= '<table border="1" cellspacing="0" cellpadding="5">';
$html .= '<tr>
            <th>Nombre</th>
            <th>Fecha Reserva</th>
            <th>Estado</th>
            <th>Libro</th>
            <th>ISBN</th>
          </tr>';

while ($fila = mysqli_fetch_assoc($result)) {
    $estado = ucfirst($fila['estado']);
    $html .= "<tr>
                <td>".htmlspecialchars($fila['nombre'])."</td>
                <td>".htmlspecialchars($fila['fecha_reserva'])."</td>
                <td>$estado</td>
                <td>".htmlspecialchars($fila['titulo'])."</td>
                <td>".htmlspecialchars($fila['isbn'])."</td>
              </tr>";
}

$html .= '</table>';

// Generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('reservas.pdf');
?>

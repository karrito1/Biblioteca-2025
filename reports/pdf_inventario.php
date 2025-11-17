<?php
session_start();
require_once("../models/MySQL.php");
require '../vendor/autoload.php';

use Dompdf\Dompdf;

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

$query = "SELECT 
 l.id AS libro_id,
 l.titulo,
 l.autor,
 l.categoria,
 l.cantidad AS cantidad_total,
 (SELECT COUNT(*) FROM prestamos p WHERE p.libro_id = l.id AND p.estado = 'activo') AS prestados,
 (SELECT COUNT(*) FROM reservas r WHERE r.libro_id = l.id AND (r.estado = 'pendiente' OR r.estado = 'aprobada')) AS reservados,
 (l.cantidad - 
  (SELECT COUNT(*) FROM prestamos p WHERE p.libro_id = l.id AND p.estado = 'activo') -
  (SELECT COUNT(*) FROM reservas r WHERE r.libro_id = l.id AND (r.estado = 'pendiente' OR r.estado = 'aprobada'))
 ) AS disponibles
FROM libros l
ORDER BY l.titulo ASC";

$result = mysqli_query($conexion, $query);

// Generar HTML
$html = '<h1>Inventario de Libros</h1>';
$html .= '<table border="1" cellspacing="0" cellpadding="5">';
$html .= '<tr>
            <th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Categoría</th>
            <th>Total</th>
            <th>Prestados</th>
            <th>Reservados</th>
            <th>Disponibles</th>
          </tr>';

while ($fila = mysqli_fetch_assoc($result)) {
    $disponibles = max($fila['disponibles'], 0);
    $html .= "<tr>
                <td>{$fila['libro_id']}</td>
                <td>".htmlspecialchars($fila['titulo'])."</td>
                <td>".htmlspecialchars($fila['autor'])."</td>
                <td>".htmlspecialchars($fila['categoria'])."</td>
                <td>{$fila['cantidad_total']}</td>
                <td>{$fila['prestados']}</td>
                <td>{$fila['reservados']}</td>
                <td>{$disponibles}</td>
              </tr>";
}

$html .= '</table>';

// Generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); // más ancho para inventario
$dompdf->render();
$dompdf->stream('inventario.pdf');
?>

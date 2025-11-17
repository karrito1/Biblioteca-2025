<?php
session_start();
require_once("../models/MySQL.php");
require '../vendor/autoload.php';

use Dompdf\Dompdf;

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Consulta de libros
$query = "SELECT id, titulo, autor, isbn, categoria, cantidad, disponibilidad, fecha_registro FROM libros";
$result = $baseDatos->efectuarConsulta($query);

// Generar HTML para PDF
$html = '<h1>Libros Registrados</h1>';
$html .= '<table border="1" cellspacing="0" cellpadding="5">';
$html .= '
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Autor</th>
        <th>ISBN</th>
        <th>Categoría</th>
        <th>Cantidad</th>
        <th>Disponibilidad</th>
        <th>Fecha Registro</th>
    </tr>
';

while ($fila = mysqli_fetch_assoc($result)) {
    $html .= "
        <tr>
            <td>{$fila['id']}</td>
            <td>" . htmlspecialchars($fila['titulo']) . "</td>
            <td>" . htmlspecialchars($fila['autor']) . "</td>
            <td>{$fila['isbn']}</td>
            <td>" . htmlspecialchars($fila['categoria']) . "</td>
            <td>{$fila['cantidad']}</td>
            <td>{$fila['disponibilidad']}</td>
            <td>{$fila['fecha_registro']}</td>
        </tr>
    ";
}

$html .= '</table>';

// Crear PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("libros.pdf");

<?php
session_start();
require_once("../models/MySQL.php");
require '../vendor/autoload.php';

use Dompdf\Dompdf;

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

$query = "SELECT id, nombre, email, telefono, direccion, estado, fecha_registro, Roles FROM usuarios";
$result = $baseDatos->efectuarConsulta($query);

// Generar HTML
$html = '<h1>Usuarios Registrados</h1>';
$html .= '<table border="1" cellspacing="0" cellpadding="5">';
$html .= '<tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Estado</th>
            <th>Fecha Registro</th>
            <th>Rol</th>
          </tr>';

while ($fila = mysqli_fetch_assoc($result)) {
    $estado = htmlspecialchars($fila['estado']);
    $html .= "<tr>
                <td>{$fila['id']}</td>
                <td>".htmlspecialchars($fila['nombre'])."</td>
                <td>".htmlspecialchars($fila['email'])."</td>
                <td>".htmlspecialchars($fila['telefono'])."</td>
                <td>".htmlspecialchars($fila['direccion'])."</td>
                <td>$estado</td>
                <td>{$fila['fecha_registro']}</td>
                <td>".htmlspecialchars($fila['Roles'])."</td>
              </tr>";
}

$html .= '</table>';

// Generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); // landscape para más columnas
$dompdf->render();
$dompdf->stream('usuarios.pdf');
?>

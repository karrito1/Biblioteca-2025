<?php
session_start();
require_once("../models/MySQL.php");
require '../vendor/autoload.php';

use Dompdf\Dompdf;

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

$usuario_id = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;
$rol = isset($_SESSION['roles']) ? strtoupper(trim($_SESSION['roles'])) : "";

// Consulta según rol
if ($rol === "ADMINISTRADOR") {
    $query = "SELECT prestamos.fecha_prestamo,
                     prestamos.fecha_devolucion,
                     prestamos.estado,
                     usuarios.nombre,
                     libros.isbn
              FROM prestamos
              LEFT JOIN usuarios ON prestamos.usuario_id = usuarios.id
              LEFT JOIN libros ON prestamos.libro_id = libros.id";
} else {
    $query = "SELECT prestamos.fecha_prestamo,
                     prestamos.fecha_devolucion,
                     prestamos.estado,
                     usuarios.nombre,
                     libros.isbn
              FROM prestamos
              LEFT JOIN usuarios ON prestamos.usuario_id = usuarios.id
              LEFT JOIN libros ON prestamos.libro_id = libros.id
              WHERE usuarios.id = $usuario_id";
}

$result = $baseDatos->efectuarConsulta($query);

// Generar HTML
$html = '<h1>Historial de Préstamos</h1>';
$html .= '<table border="1" cellspacing="0" cellpadding="5">';
$html .= '<tr>
            <th>Fecha Préstamo</th>
            <th>Fecha Devolución</th>
            <th>Estado</th>
            <th>Usuario</th>
            <th>ISBN</th>
          </tr>';

while ($fila = mysqli_fetch_assoc($result)) {
    $html .= "<tr>
                <td>{$fila['fecha_prestamo']}</td>
                <td>{$fila['fecha_devolucion']}</td>
                <td>{$fila['estado']}</td>
                <td>{$fila['nombre']}</td>
                <td>{$fila['isbn']}</td>
              </tr>";
}

$html .= '</table>';

// Generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('prestamos.pdf');

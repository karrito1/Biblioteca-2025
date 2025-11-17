<?php
session_start();
require_once("../models/MySQL.php");
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['roles'])) {
    die("Error: sesión no válida.");
}

$usuario_id = (int) $_SESSION['usuario_id'];
$rol = strtoupper(trim($_SESSION['roles']));

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

// Crear Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Reservas');

// Encabezados
$headers = ['Nombre', 'Fecha Reserva', 'Estado', 'Libro', 'ISBN'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

// Contenido
$filaExcel = 2;
while ($fila = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue("A$filaExcel", $fila['nombre']);
    $sheet->setCellValue("B$filaExcel", $fila['fecha_reserva']);
    $sheet->setCellValue("C$filaExcel", ucfirst($fila['estado']));
    $sheet->setCellValue("D$filaExcel", $fila['titulo']);
    $sheet->setCellValue("E$filaExcel", $fila['isbn']);
    $filaExcel++;
}

// Descargar Excel
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="reservas.xlsx"');
$writer->save('php://output');
exit;

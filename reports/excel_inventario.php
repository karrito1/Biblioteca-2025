<?php
session_start();
require_once("../models/MySQL.php");
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

// Crear Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Inventario');

// Encabezados
$headers = ['ID', 'Título', 'Autor', 'Categoría', 'Total', 'Prestados', 'Reservados', 'Disponibles'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

// Contenido
$filaExcel = 2;
while ($fila = mysqli_fetch_assoc($result)) {
    $disponibles = max($fila['disponibles'], 0);
    $sheet->setCellValue("A$filaExcel", $fila['libro_id']);
    $sheet->setCellValue("B$filaExcel", $fila['titulo']);
    $sheet->setCellValue("C$filaExcel", $fila['autor']);
    $sheet->setCellValue("D$filaExcel", $fila['categoria']);
    $sheet->setCellValue("E$filaExcel", $fila['cantidad_total']);
    $sheet->setCellValue("F$filaExcel", $fila['prestados']);
    $sheet->setCellValue("G$filaExcel", $fila['reservados']);
    $sheet->setCellValue("H$filaExcel", $disponibles);
    $filaExcel++;
}

// Descargar Excel
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="inventario.xlsx"');
$writer->save('php://output');
exit;

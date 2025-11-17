<?php
session_start();
require_once("../models/MySQL.php");
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Consulta
$query = "SELECT id, titulo, autor, isbn, categoria, cantidad, disponibilidad, fecha_registro FROM libros";
$result = $baseDatos->efectuarConsulta($query);

// Crear libro Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Libros');

// Encabezados
$encabezados = [
    'ID', 'Título', 'Autor', 'ISBN', 'Categoría',
    'Cantidad', 'Disponibilidad', 'Fecha Registro'
];

$col = 'A';
foreach ($encabezados as $titulo) {
    $sheet->setCellValue($col . "1", $titulo);
    $col++;
}

// Datos
$filaExcel = 2;
while ($fila = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue("A$filaExcel", $fila['id']);
    $sheet->setCellValue("B$filaExcel", $fila['titulo']);
    $sheet->setCellValue("C$filaExcel", $fila['autor']);
    $sheet->setCellValue("D$filaExcel", $fila['isbn']);
    $sheet->setCellValue("E$filaExcel", $fila['categoria']);
    $sheet->setCellValue("F$filaExcel", $fila['cantidad']);
    $sheet->setCellValue("G$filaExcel", $fila['disponibilidad']);
    $sheet->setCellValue("H$filaExcel", $fila['fecha_registro']);
    $filaExcel++;
}

// Descargar archivo
$writer = new Xlsx($spreadsheet);
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Disposition: attachment; filename="libros.xlsx"');
$writer->save('php://output');
exit;
?>

<?php
session_start();
require_once("../models/MySQL.php");
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

$query = "SELECT id, nombre, email, telefono, direccion, estado, fecha_registro, Roles FROM usuarios";
$result = $baseDatos->efectuarConsulta($query);

// Crear Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Usuarios');

// Encabezados
$headers = ['ID', 'Nombre', 'Email', 'Teléfono', 'Dirección', 'Estado', 'Fecha Registro', 'Rol'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

// Contenido
$filaExcel = 2;
while ($fila = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue("A$filaExcel", $fila['id']);
    $sheet->setCellValue("B$filaExcel", $fila['nombre']);
    $sheet->setCellValue("C$filaExcel", $fila['email']);
    $sheet->setCellValue("D$filaExcel", $fila['telefono']);
    $sheet->setCellValue("E$filaExcel", $fila['direccion']);
    $sheet->setCellValue("F$filaExcel", $fila['estado']);
    $sheet->setCellValue("G$filaExcel", $fila['fecha_registro']);
    $sheet->setCellValue("H$filaExcel", $fila['Roles']);
    $filaExcel++;
}

// Descargar Excel
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="usuarios.xlsx"');
$writer->save('php://output');
exit;

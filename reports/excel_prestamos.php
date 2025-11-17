<?php
session_start();
require_once("../models/MySQL.php");
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

$usuario_id = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;
$rol = isset($_SESSION['roles']) ? strtoupper(trim($_SESSION['roles'])) : "";

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

// Crear Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Prestamos');

// Encabezados
$sheet->setCellValue('A1', 'Fecha Préstamo');
$sheet->setCellValue('B1', 'Fecha Devolución');
$sheet->setCellValue('C1', 'Estado');
$sheet->setCellValue('D1', 'Usuario');
$sheet->setCellValue('E1', 'ISBN');

// Contenido
$filaExcel = 2;
while ($fila = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue("A$filaExcel", $fila['fecha_prestamo']);
    $sheet->setCellValue("B$filaExcel", $fila['fecha_devolucion']);
    $sheet->setCellValue("C$filaExcel", $fila['estado']);
    $sheet->setCellValue("D$filaExcel", $fila['nombre']);
    $sheet->setCellValue("E$filaExcel", $fila['isbn']);
    $filaExcel++;
}

// Descargar Excel
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="prestamos.xlsx"');
$writer->save('php://output');
exit;

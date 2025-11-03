<?php
require_once("../models/MySQL.php");

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(["success" => false, "message" => "ID no válido"]);
    exit;
}

$db = new MySQL();
$con = $db->conectar();

// Obtener datos del préstamo
$queryPrestamo = "SELECT * FROM prestamos WHERE id = $id LIMIT 1";
$resultPrestamo = $db->efectuarConsulta($queryPrestamo);

if (!$resultPrestamo || mysqli_num_rows($resultPrestamo) == 0) {
    echo json_encode(["success" => false, "message" => "No encontrado"]);
    exit;
}

$prestamo = mysqli_fetch_assoc($resultPrestamo);

// Cargar usuarios select
$usuarios = "";
$resUsuarios = $db->efectuarConsulta("SELECT id,nombre FROM usuarios");
while ($row = mysqli_fetch_assoc($resUsuarios)) {
    $usuarios .= "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
}

// Cargar libros select
$libros = "";
$resLibros = $db->efectuarConsulta("SELECT id,isbn FROM libros");
while ($row = mysqli_fetch_assoc($resLibros)) {
    $libros .= "<option value='" . $row['id'] . "'>" . $row['isbn'] . "</option>";
}

echo json_encode([
    "success" => true,
    "prestamo" => $prestamo,
    "usuarios" => $usuarios,
    "libros" => $libros
]);

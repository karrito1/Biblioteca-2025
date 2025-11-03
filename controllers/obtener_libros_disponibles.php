<?php
require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Consulta para traer libros disponibles
$consulta = "SELECT id, titulo FROM libros WHERE disponibilidad = 'disponible'";

$resultado = $baseDatos->efectuarConsulta($consulta);

$libros = array();

// Convertir resultados a JSON
while ($row = mysqli_fetch_assoc($resultado)) {
    $libros[] = $row;
}

echo json_encode(["libros" => $libros]);

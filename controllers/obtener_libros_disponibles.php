<?php
require_once("../models/MySQL.php");

header("Content-Type: application/json; charset=utf-8");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

$query = "SELECT id, titulo, autor, disponibilidad 
          FROM libros 
          WHERE disponibilidad > 0";

$resultado = $baseDatos->efectuarConsulta($query);

$libros = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $libros[] = $fila;
}

$baseDatos->desconectar();

echo json_encode($libros);

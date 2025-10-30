<?php
require_once("../models/MySQL.php");

header("Content-Type: application/json; charset=utf-8");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

try {
    $query = "SELECT id, titulo, autor, isbn, disponibilidad 
              FROM libros 
              WHERE disponibilidad = 'disponible'
              ORDER BY titulo ASC";

    $resultado = $baseDatos->efectuarConsulta($query);

    if (!$resultado) {
        throw new Exception("Error al consultar los libros");
    }

    $libros = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $libros[] = $fila;
    }

    echo json_encode([
        'success' => true,
        'libros' => $libros
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => "Error: " . $e->getMessage()
    ]);
} finally {
    $baseDatos->desconectar();
}

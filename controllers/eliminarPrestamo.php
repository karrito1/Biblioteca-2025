<?php
require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Recibir id
$id = intval($_POST['id']);

// Eliminar en BD
$query = "DELETE FROM prestamos WHERE id = $id";
$resultado = $baseDatos->efectuarConsulta($query);

// Respuesta al JS
if ($resultado) {
    echo "ok";
} else {
    echo "error";
}

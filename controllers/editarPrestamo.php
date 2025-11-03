<?php
require_once("../models/MySQL.php");

$id = intval($_POST['editar_prestamo_id'] ?? 0);
$usuario = intval($_POST['editar_usuario_id'] ?? 0);
$libro = intval($_POST['editar_libro_id'] ?? 0);
$fechaP = $_POST['editar_fecha_prestamo'] ?? "";
$fechaD = $_POST['editar_fecha_devolucion'] ?? "";
$estado = $_POST['editar_estado'] ?? "";

if ($id <= 0 || $usuario <= 0 || $libro <= 0) {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    exit;
}

$db = new MySQL();
$con = $db->conectar();

$query = "UPDATE prestamos SET 
            usuario_id = $usuario,
            libro_id = $libro,
            fecha_prestamo = '$fechaP',
            fecha_devolucion = '$fechaD',
            estado = '$estado'
          WHERE id = $id";

if ($db->efectuarConsulta($query)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "No se pudo actualizar"]);
}

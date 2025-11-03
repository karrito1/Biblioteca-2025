<?php
session_start();
require_once("../models/MySQL.php");

$db = new MySQL();
$conn = $db->conectar();

$usuario = intval($_POST["usuario_id"]);
$libro = intval($_POST["libro_id"]);

$sql = "INSERT INTO reservas (usuario_id, libro_id, fecha_reserva, estado)
        VALUES ($usuario, $libro, NOW(), 'pendiente')";

$res = $db->efectuarConsulta($sql);

echo json_encode(["success" => $res]);
$db->desconectar();

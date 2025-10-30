<?php
header("Content-Type: application/json; charset=utf-8");
session_start();
include_once "../models/MySQL.php";

$respuesta = ["success" => false, "message" => "No se pudo obtener la información del usuario."];

if (isset($_SESSION["usuario_id"])) {
    try {
        $db = new MySQL();
        $conexion = $db->conectar();

        $idUsuario = intval($_SESSION["usuario_id"]);
        $consulta = "SELECT id, nombre, email, telefono, direccion FROM usuarios WHERE id = ?";
        $stmt = $conexion->prepare($consulta);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        $resultado = $stmt->get_result();
        if ($resultado && $fila = $resultado->fetch_assoc()) {
            $respuesta = [
                "success" => true,
                "usuario" => $fila
            ];
        } else {
            $respuesta = ["success" => false, "message" => "Usuario no encontrado."];
        }

        $stmt->close();
        $db->desconectar();
    } catch (Exception $e) {
        $respuesta = ["success" => false, "message" => $e->getMessage()];
    }
} else {
    $respuesta = ["success" => false, "message" => "Sesión no iniciada."];
}

echo json_encode($respuesta);

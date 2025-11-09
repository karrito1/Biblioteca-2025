<?php
header("Content-Type: application/json; charset=utf-8");
session_start();
include_once "../models/MySQL.php";

$response = ["success" => false, "message" => "No se pudo obtener el usuario."];

if (!isset($_SESSION["usuario_id"])) {
    $response["message"] = "Sesión no iniciada.";
    echo json_encode($response);
    exit;
}

try {
    $id = intval($_SESSION["usuario_id"]);
    $db = new MySQL();
    $conexion = $db->conectar();

    $query = "SELECT nombre, email, telefono, direccion FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $response = [
            "success" => true,
            "usuario" => $usuario
        ];
    } else {
        $response["message"] = "Usuario no encontrado.";
    }

    $stmt->close();
    $db->desconectar();
} catch (Exception $e) {
    $response["message"] = $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);

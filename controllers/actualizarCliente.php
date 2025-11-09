<?php
header("Content-Type: application/json; charset=utf-8");
session_start();
include_once "../models/MySQL.php";

$response = ["success" => false, "message" => "Error al actualizar."];

// Verificar si hay sesión
if (!isset($_SESSION["usuario_id"])) {
    echo json_encode(["success" => false, "message" => "Sesión no iniciada."]);
    exit;
}

// Verificar método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idUsuario = $_SESSION["usuario_id"];
    $nombre = $_POST["nombreCliente"] ?? '';
    $email = $_POST["emailCliente"] ?? '';
    $telefono = $_POST["telefonoCliente"] ?? '';
    $direccion = $_POST["direccionCliente"] ?? '';
    $password = $_POST["passworddCliente"] ?? '';

    try {
        $db = new MySQL();
        $conexion = $db->conectar();

        if (!empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE usuarios SET nombre=?, email=?, telefono=?, direccion=?, passwordd=? WHERE id=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssssi", $nombre, $email, $telefono, $direccion, $passwordHash, $idUsuario);
        } else {
            $sql = "UPDATE usuarios SET nombre=?, email=?, telefono=?, direccion=? WHERE id=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssssi", $nombre, $email, $telefono, $direccion, $idUsuario);
        }

        if ($stmt->execute()) {
            $response = ["success" => true, "message" => "Datos actualizados correctamente."];
        } else {
            $response["message"] = "No se pudo actualizar.";
        }

        $stmt->close();
        $db->desconectar();

    } catch (Exception $e) {
        $response["message"] = "Error: " . $e->getMessage();
    }
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);


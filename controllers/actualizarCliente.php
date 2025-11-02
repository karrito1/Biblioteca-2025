<?php
header("Content-Type: application/json; charset=utf-8");
session_start();
include_once "../models/MySQL.php";

$response = ["success" => false, "message" => "Solicitud invalida."];

// Verificar si hay sesion activa
if (!isset($_SESSION["usuario_id"])) {
    $response["message"] = "Sesion no iniciada.";
    echo json_encode($response);
    exit;
}

// Solo aceptar metodo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $idUsuario = intval($_SESSION["usuario_id"]);
    $nombre     = htmlspecialchars(trim($_POST["nombre"] ?? ""));
    $email      = filter_var(trim($_POST["email"] ?? ""), FILTER_SANITIZE_EMAIL);
    $telefono   = htmlspecialchars(trim($_POST["telefono"] ?? ""));
    $direccion  = htmlspecialchars(trim($_POST["direccion"] ?? ""));
    $password   = $_POST["passwordd"] ?? "";

    // Validacion de campos minimos
    if (empty($nombre) || empty($email)) {
        $response["message"] = "Los campos nombre y correo son obligatorios.";
        echo json_encode($response);
        exit;
    }

    try {
        $db = new MySQL();
        $conexion = $db->conectar();

        if (!empty($password)) {
            // Si el usuario cambio su contrasena
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $query = "UPDATE usuarios SET 
                        nombre = ?, 
                        email = ?, 
                        telefono = ?, 
                        direccion = ?, 
                        passwordd = ?
                      WHERE id = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("sssssi", $nombre, $email, $telefono, $direccion, $passwordHash, $idUsuario);
        } else {
            // Sin cambiar contrasena
            $query = "UPDATE usuarios SET 
                        nombre = ?, 
                        email = ?, 
                        telefono = ?, 
                        direccion = ?
                      WHERE id = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("ssssi", $nombre, $email, $telefono, $direccion, $idUsuario);
        }

        if ($stmt->execute()) {
            $response = ["success" => true, "message" => "Datos actualizados correctamente."];
        } else {
            $response["message"] = "No se pudo actualizar la informacion.";
        }

        $stmt->close();
        $db->desconectar();

    } catch (Exception $e) {
        $response["message"] = $e->getMessage();
    }
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);


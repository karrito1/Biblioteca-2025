<?php
header("Content-Type: application/json; charset=utf-8");
session_start();
include_once "../models/MySQL.php";

$respuesta = ["success" => false, "message" => "Solicitud inválida."];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset($_SESSION["usuario_id"]) &&
        !empty($_POST["nombre"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["telefono"]) &&
        !empty($_POST["direccion"])
    ) {
        $id         = intval($_SESSION["usuario_id"]); // el cliente solo puede modificar su propio id
        $nombre     = htmlspecialchars(trim($_POST["nombre"]));
        $email      = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $telefono   = htmlspecialchars(trim($_POST["telefono"]));
        $direccion  = htmlspecialchars(trim($_POST["direccion"]));
        $passwordd  = isset($_POST["passwordd"]) && !empty($_POST["passwordd"])
            ? password_hash($_POST["passwordd"], PASSWORD_BCRYPT)
            : null;

        try {
            $db = new MySQL();
            $conexion = $db->conectar();

            if ($passwordd) {
                $consulta = "UPDATE usuarios 
                             SET nombre = ?, email = ?, telefono = ?, direccion = ?, passwordd = ?
                             WHERE id = ?";
                $datos = $conexion->prepare($consulta);
                $datos->bind_param("sssssi", $nombre, $email, $telefono, $direccion, $passwordd, $id);
            } else {
                $consulta = "UPDATE usuarios 
                             SET nombre = ?, email = ?, telefono = ?, direccion = ?
                             WHERE id = ?";
                $datos = $conexion->prepare($consulta);
                $datos->bind_param("ssssi", $nombre, $email, $telefono, $direccion, $id);
            }

            if ($datos->execute()) {
                $respuesta = ["success" => true, "message" => "Datos actualizados correctamente."];
            } else {
                $respuesta = ["success" => false, "message" => "Error al actualizar tus datos."];
            }

            $datos->close();
            $db->desconectar();
        } catch (Exception $e) {
            $respuesta = ["success" => false, "message" => $e->getMessage()];
        }
    } else {
        $respuesta = ["success" => false, "message" => "Por favor complete todos los campos requeridos."];
    }
}

echo json_encode($respuesta);

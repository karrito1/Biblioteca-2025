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
        $id        = $_SESSION["usuario_id"];
        $nombre    = $_POST["nombre"];
        $email     = $_POST["email"];
        $telefono  = $_POST["telefono"];
        $direccion = $_POST["direccion"];
        $passwordd = !empty($_POST["passwordd"]) ? $_POST["passwordd"] : null;

        try {
            $db = new MySQL();
            $conexion = $db->conectar();

            if ($passwordd) {
                $consulta = "UPDATE usuarios 
                             SET nombre='$nombre', email='$email', telefono='$telefono', direccion='$direccion', passwordd='$passwordd'
                             WHERE id='$id'";
            } else {
                $consulta = "UPDATE usuarios 
                             SET nombre='$nombre', email='$email', telefono='$telefono', direccion='$direccion'
                             WHERE id='$id'";
            }

            if ($conexion->query($consulta)) {
                $respuesta = ["success" => true, "message" => "Datos actualizados correctamente."];
            } else {
                $respuesta = ["success" => false, "message" => "Error al actualizar los datos."];
            }

            $db->desconectar();
        } catch (Exception $e) {
            $respuesta = ["success" => false, "message" => $e->getMessage()];
        }
    } else {
        $respuesta = ["success" => false, "message" => "Complete todos los campos requeridos."];
    }
}

echo json_encode($respuesta);

<?php
session_start();

include_once "../models/MySQL.php";

$respuesta = ["success" => false, "message" => "No se pudo obtener la información del usuario."];

if (isset($_SESSION["usuario_id"])) {
    try {
        $baseDatos = new MySQL();
        $conexion = $baseDatos->conectar();

        $idUsuario = intval($_SESSION["usuario_id"]);

        $consulta = "SELECT id, nombre, email, telefono, direccion 
                     FROM usuarios 
                     WHERE id = ?";

        $datosConsulta = $conexion->prepare($consulta);
        $datosConsulta->bind_param("i", $idUsuario);
        $datosConsulta->execute();
        $resultado = $datosConsulta->get_result();

        if ($resultado && $fila = $resultado->fetch_assoc()) {
            $respuesta = [
                "success" => true,
                "usuario" => $fila
            ];
        } else {
            $respuesta = ["success" => false, "message" => "Usuario no encontrado."];
        }

        $datosConsulta->close();
        $baseDatos->desconectar();
    } catch (Exception $e) {
        $respuesta = ["success" => false, "message" => $e->getMessage()];
    }
} else {
    $respuesta = ["success" => false, "message" => "Sesion no iniciada."];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($respuesta);

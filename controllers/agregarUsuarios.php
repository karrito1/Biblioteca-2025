<?php
header("Content-Type: application/json; charset=utf-8");
include_once "../models/MySQL.php";
$baseDatos = new MySQL();
$baseDatos->conectar();

$response = ["status" => "error", "message" => "Solicitud inválida."];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        !empty($_POST["nombre"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["telefono"]) &&
        !empty($_POST["direccion"]) &&
        !empty($_POST["estado"]) &&
        !empty($_POST["fecha_registro"]) &&
        !empty($_POST["Roles"]) &&
        !empty($_POST["passwordd"])
    ) {

        $nombre        = htmlspecialchars(trim($_POST["nombre"]));
        $correo        = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $telefono      = htmlspecialchars(trim($_POST["telefono"]));
        $direccion     = htmlspecialchars(trim($_POST["direccion"]));
        $estado        = htmlspecialchars(trim($_POST["estado"]));
        $fechaRegistro = htmlspecialchars($_POST["fecha_registro"]);
        $roles         = htmlspecialchars(trim($_POST["Roles"]));
        $passwordPlano = $_POST["passwordd"];
        $hash          = password_hash($passwordPlano, PASSWORD_BCRYPT);

        try {
            $baseDatos = new MySQL();
            $baseDatos->conectar();

            $consulta = "INSERT INTO usuarios 
            (nombre, email, telefono, direccion, estado, fecha_registro, Roles, passwordd) 
            VALUES ('$nombre','$correo','$telefono','$direccion','$estado','$fechaRegistro','$roles','$hash')";

            if ($baseDatos->efectuarConsulta($consulta)) {
                $response = ["status" => "success", "message" => "Registro insertado correctamente."];
            }

            $baseDatos->desconectar();
        } catch (Exception $e) {
            $response = ["status" => "error", "message" => $e->getMessage()];
        }
    } else {
        $response = ["status" => "error", "message" => "Por favor complete todos los campos."];
    }
}

echo json_encode($response);

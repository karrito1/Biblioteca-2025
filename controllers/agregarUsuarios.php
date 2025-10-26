<?php
header("Content-Type: application/json; charset=utf-8");
<<<<<<< HEAD
require_once "../models/MySQL.php";

$mysql = new MySQL();
$mysql->conectar();

$response = ["status" => "error", "message" => "solicitud invalida."];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validar campos obligatorios
    if (
        !empty($_POST["nombre"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["Roles"]) &&
        !empty($_POST["passwordd"]) &&
        !empty($_POST["estado"]) &&
        !empty($_POST["telefono"]) &&
        !empty($_POST["direccion"])
    ) {
        // Sanitizar y preparar datos
        $nombre    = htmlspecialchars(trim($_POST["nombre"]));
        $email     = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $roles     = htmlspecialchars(trim($_POST["Roles"]));
        $password  = password_hash($_POST["passwordd"], PASSWORD_BCRYPT);
        $estado    = htmlspecialchars(trim($_POST["estado"]));
        $telefono  = htmlspecialchars(trim($_POST["telefono"]));
        $direccion = htmlspecialchars(trim($_POST["direccion"]));
        $fecha     = !empty($_POST["fecha_registro"]) ? $_POST["fecha_registro"] : date("Y-m-d H:i:s");

        // Insertar en la base de datos
        $insertar = "INSERT INTO usuarios 
            (nombre, email, telefono, direccion, estado, fecha_registro, Roles, passwordd) 
            VALUES 
            ('$nombre', '$email', '$telefono', '$direccion', '$estado', '$fecha', '$roles', '$password')";

        if ($mysql->efectuarConsulta($insertar)) {
            $response = ["status" => "success", "message" => "Usuario registrado correctamente."];
        } else {
            $response = ["status" => "error", "message" => "Error al registrar usuario: " . $mysql->getError()];
        }
    } else {
        $response = ["status" => "error", "message" => "Por favor complete todos los campos obligatorios."];
    }
}

$mysql->desconectar();
=======
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

>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
echo json_encode($response);

<?php
header("Content-Type: application/json; charset=utf-8");
include_once "../models/MySQL.php";

$baseDatos = new MySQL();
$baseDatos->conectar();

$response = ["status" => "error", "message" => "Solicitud inválida."];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["id"] ?? 0);
    $nombre = trim($_POST["nombre"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $direccion = trim($_POST["direccion"] ?? "");
    $estado = trim($_POST["estado"] ?? "");
    $fecha_registro = $_POST["fecha_registro"] ?? "";
    $Roles = trim($_POST["Roles"] ?? "");
    $passwordd = $_POST["passwordd"] ?? "";

    if ($id && $nombre && $email && $telefono && $direccion && $estado && $fecha_registro && $Roles) {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $passwordSQL = !empty($passwordd) ? ", passwordd='" . password_hash($passwordd, PASSWORD_BCRYPT) . "'" : "";

        $sql = "UPDATE usuarios SET 
                nombre='$nombre',
                email='$email',
                telefono='$telefono',
                direccion='$direccion',
                estado='$estado',
                fecha_registro='$fecha_registro',
                Roles='$Roles' $passwordSQL
                WHERE id=$id";

        if ($baseDatos->efectuarConsulta($sql)) {
            $response = ["status" => "success", "message" => "Usuario actualizado correctamente."];
        } else {
            $response = ["status" => "error", "message" => "No se pudo actualizar el usuario."];
        }
    } else {
        $response = ["status" => "error", "message" => "Complete todos los campos."];
    }
}

$baseDatos->desconectar();
echo json_encode($response);

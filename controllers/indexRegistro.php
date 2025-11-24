<?php
session_start();
include_once "../models/MySQL.php";

$baseDatos = new MySQL();
$baseDatos->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? '';
    $email = $_POST["email"] ?? '';
    $telefono = $_POST["telefono"] ?? '';
    $direccion = $_POST["direccion"] ?? '';
    $passwordd = $_POST["passwordd"] ?? '';

    // Validar campos obligatorios
    if (!empty($nombre) && !empty($email) && !empty($passwordd)) {

        // Validar que no haya caracteres especiales
        if (!preg_match("/^[a-zA-Z0-9\s]+$/", $nombre)) {
            header("Location: ../index.php?registro=error_nombre");
            exit;
        }
        if (!preg_match("/^[a-zA-Z0-9\s]+$/", $direccion)) {
            header("Location: ../index.php?registro=error_direccion");
            exit;
        }
        if (!empty($telefono) && !preg_match("/^[0-9]+$/", $telefono)) {
            header("Location: ../index.php?registro=error_telefono");
            exit;
        }

        // Rol por defecto y estado activo
        $rol = "cliente";
        $estado = "activo";

        // Hashear la contraseña
        $password_hash = password_hash($passwordd, PASSWORD_DEFAULT);

        // Insertar en la BD
        $sql = "INSERT INTO usuarios (nombre, email, telefono, direccion, passwordd, Roles, estado) 
                VALUES ('$nombre', '$email', '$telefono', '$direccion', '$password_hash', '$rol', '$estado')";

        try {
            if ($baseDatos->efectuarConsulta($sql)) {
                header("Location: ../index.php?registro=ok");
                exit;
            } else {
                header("Location: ../index.php?registro=error");
                exit;
            }
        } catch (mysqli_sql_exception $e) {
            if (strpos($e->getMessage(), "Duplicate entry") !== false) {
                header("Location: ../index.php?registro=duplicado");
                exit;
            } else {
                header("Location: ../index.php?registro=error");
                exit;
            }
        }

    } else {
        // Faltan datos obligatorios
        header("Location: ../index.php?registro=faltan");
        exit;
    }
}

$baseDatos->desconectar();
?>

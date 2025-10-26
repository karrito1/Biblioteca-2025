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
        // Rol por defecto y estado activo
        $rol = "cliente";
        $estado = "activo";

        // hashear la contrasena
        $password_hash = password_hash($passwordd, PASSWORD_DEFAULT);

        // Insertar en la BD
        $sql = "INSERT INTO usuarios (nombre, email, telefono, direccion, passwordd, Roles, estado) 
                VALUES ('$nombre', '$email', '$telefono', '$direccion', '$password_hash', '$rol', '$estado')";

        if ($baseDatos->efectuarConsulta($sql)) {

            header("Location: ../index.php?registro=ok");
            exit;
        } else {
            // Error en la BD
            header("Location: ../index.php?registro=error");
            exit;
        }
    } else {
        // Faltan datos obligatorios
        header("Location: ../index.php?registro=faltan");
        exit;
    }
}

$baseDatos->desconectar();

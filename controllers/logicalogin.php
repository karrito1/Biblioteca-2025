<?php
require_once __DIR__ . '/../models/MySQL.php';

session_start();

if (isset($_POST['email'], $_POST['password'])) {

    $mysql = new MySQL();
    $conexion = $mysql->conectar();

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Preparar consulta segura
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($usuario = $resultado->fetch_assoc()) {

        // Verificar estado
        if ($usuario['estado'] !== 'activo') {
            $mysql->desconectar();
            header("Location: ../index.php?error=1");
            exit();
        }

        if (md5($password) === $usuario['passwordd']) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['roles'] = $usuario['Roles'];

            // Redirigir según rol
            if ($usuario['Roles'] === 'ADMINISTRADOR') {
                $mysql->desconectar();
                header("Location: ../views/dashboard.php");
                exit();
            } elseif ($usuario['Roles'] === 'CLIENTE') {
                $mysql->desconectar();
                header("Location: ../views/info_usuario.php");
                exit();
            } else {
                $mysql->desconectar();
                header("Location: ../index.php?error=1");
                exit();
            }
        } else {
            $mysql->desconectar();
            header("Location: ../index.php?error=1");
            exit();
        }
    } else {
        $mysql->desconectar();
        header("Location: ../index.php?error=1");
        exit();
    }
} else {
    // Campos no enviados
    header("Location: ../index.php?error=1");
    exit();
}

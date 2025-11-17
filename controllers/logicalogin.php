<?php
require_once __DIR__ . '/../models/MySQL.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $mysql = new MySQL();
    $conexion = $mysql->conectar();

    $sql = "SELECT id, nombre, email, passwordd, Roles, estado 
            FROM usuarios 
            WHERE email = '$email'
            LIMIT 1";

    $resultado = mysqli_query($conexion, $sql);

    if ($usuario = mysqli_fetch_assoc($resultado)) {

        if (strtolower($usuario['estado']) !== 'activo') {
            $mysql->desconectar();
            header("Location: ../index.php?login=inactivo");
            exit();
        }

        if (password_verify($password, $usuario['passwordd'])) {

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['roles'] = strtoupper($usuario['Roles']);

            switch ($_SESSION['roles']) {
                case 'ADMINISTRADOR':
                    header("Location: ../views/dashboard.php?login=ok");
                    break;

                case 'CLIENTE':
                    header("Location: ../views/dashboard.php?login=ok");
                    break;

                default:
                    header("Location: ../index.php?login=rol");
                    break;
            }

            $mysql->desconectar();
            exit();
        } else {

            $mysql->desconectar();
            header("Location: ../index.php?login=incorrecto");
            exit();
        }
    } else {

        $mysql->desconectar();
        header("Location: ../index.php?login=noexiste");
        exit();
    }
} else {
    header("Location: ../index.php?login=nopermitido");
    exit();
}

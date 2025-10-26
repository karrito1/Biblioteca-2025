<?php
require_once __DIR__ . '/../models/MySQL.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $mysql = new MySQL();
    $conexion = $mysql->conectar();


    $ResultadoConsulta = $conexion->prepare("SELECT id, nombre, email, passwordd, Roles, estado FROM usuarios WHERE email = ?");
    $ResultadoConsulta->bind_param("s", $email);
    $ResultadoConsulta->execute();
    $resultado = $ResultadoConsulta->get_result();

    if ($usuario = $resultado->fetch_assoc()) {


        if (strtolower($usuario['estado']) !== 'activo') {
            $mysql->desconectar();
            header("Location: ../index.php?error=Usuario inactivo");
            exit();
        }
        if (password_verify($password, $usuario['passwordd'])) {

<<<<<<< HEAD
            // crear sesion
=======
            // Crear sesión
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['roles'] = strtoupper($usuario['Roles']);


            switch ($_SESSION['roles']) {
                case 'ADMINISTRADOR':
                    header("Location: ../views/dashboard.php");
                    break;

                case 'CLIENTE':
                    header("Location: ../views/dashboard.php");
                    break;

                default:
                    header("Location: ../index.php?error=Rol no autorizado");
                    break;
            }

            $mysql->desconectar();
            exit();
        } else {

            $mysql->desconectar();
<<<<<<< HEAD
            header("Location: ../index.php?error=contrasena incorrecta");
=======
            header("Location: ../index.php?error=Contraseña incorrecta");
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
            exit();
        }
    } else {
        // Usuario no encontrado
        $mysql->desconectar();
        header("Location: ../index.php?error=Usuario no encontrado");
        exit();
    }
} else {
<<<<<<< HEAD
    // acceso no valido
=======
    // Acceso no válido
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
    header("Location: ../index.php?error=Acceso no permitido");
    exit();
}

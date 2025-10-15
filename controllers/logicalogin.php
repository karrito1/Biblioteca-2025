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
        if (md5($password) === $usuario['passwordd']) {

            // Crear sesión
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
            header("Location: ../index.php?error=Contraseña incorrecta");
            exit();
        }
    } else {
        // Usuario no encontrado
        $mysql->desconectar();
        header("Location: ../index.php?error=Usuario no encontrado");
        exit();
    }
} else {
    // Acceso no válido
    header("Location: ../index.php?error=Acceso no permitido");
    exit();
}

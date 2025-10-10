<?php
require_once '../models/MySQL.php';
session_start();

if (isset($_POST['numeroDocumento'], $_POST['passwordd'])) {
    $mysql = new MySQL();
    $mysql->conectar();

    $numeroDocumento = trim($_POST['numeroDocumento']);
    $password = $_POST['passwordd'];

    // Buscar usuario por documento
    $consulta = "SELECT * FROM empleados WHERE numeroDocumento = '$numeroDocumento'";
    $resultado = $mysql->efectuarConsulta($consulta);

    if ($usuario = mysqli_fetch_assoc($resultado)) {
        if ($usuario['estado'] !== 'ACTIVO') {
            $mysql->desconectar();
            header("Location: ../index.php?error=1");
            exit();
        }
        // Verificar contraseña
        if (password_verify($password, $usuario['passwordd'])) {
            // Guardar datos en sesión
            $_SESSION['usuario_id'] = $usuario['idempleados'];
            $_SESSION['numeroDocumento'] = $usuario['numeroDocumento'];
            $_SESSION['idcargo'] = $usuario['cargo_idcargo'];
            if ($usuario['cargo_idcargo'] == 1) {
                // Administrador
                $mysql->desconectar();
                header("Location: ../views/usuarios.php");
                exit();
            }
            if ($usuario['cargo_idcargo'] == 2) {
                // Empleado
                $mysql->desconectar();
                header("Location: ../views/info_usuario.php");
                exit();
            }
        }

        $mysql->desconectar();
        header("Location: ../index.php?error=1");
        exit();
    } else {

        header("Location: ../index.php?error=1");
        exit();
    }
}

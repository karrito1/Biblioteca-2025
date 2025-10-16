<?php
header("Content-Type: application/json; charset=utf-8");
include_once "../models/MySQL.php";

$baseDatos = new MySQL();
$baseDatos->conectar();

$responsable = ["status" => "error", "message" => "Solicitud inválida."];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        !empty($_POST["id"]) &&
        !empty($_POST["nombre"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["telefono"]) &&
        !empty($_POST["direccion"]) &&
        !empty($_POST["estado"]) &&
        !empty($_POST["fecha_registro"]) &&
        !empty($_POST["Roles"])
    ) {

        $id            = intval($_POST["id"]);
        $nombre        = htmlspecialchars(trim($_POST["nombre"]));
        $correo        = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $telefono      = htmlspecialchars(trim($_POST["telefono"]));
        $direccion     = htmlspecialchars(trim($_POST["direccion"]));
        $estado        = htmlspecialchars(trim($_POST["estado"]));
        $fechaRegistro = htmlspecialchars($_POST["fecha_registro"]);
        $roles         = htmlspecialchars(trim($_POST["Roles"]));

        // Si se envía una nueva contraseña, se actualiza el hash
        $passwordPlano = isset($_POST["passwordd"]) && !empty($_POST["passwordd"])
            ? password_hash($_POST["passwordd"], PASSWORD_BCRYPT)
            : null;

        try {
            if ($passwordPlano) {
                $consulta = "UPDATE usuarios SET 
                    nombre='$nombre',
                    email='$correo',
                    telefono='$telefono',
                    direccion='$direccion',
                    estado='$estado',
                    fecha_registro='$fechaRegistro',
                    Roles='$roles',
                    passwordd='$passwordPlano'
                    WHERE id=$id";
            } else {
                $consulta = "UPDATE usuarios SET 
                    nombre='$nombre',
                    email='$correo',
                    telefono='$telefono',
                    direccion='$direccion',
                    estado='$estado',
                    fecha_registro='$fechaRegistro',
                    Roles='$roles'
                    WHERE id=$id";
            }

            if ($baseDatos->efectuarConsulta($consulta)) {
                $responsable = ["status" => "success", "message" => "Usuario actualizado correctamente."];
            }

            $baseDatos->desconectar();
        } catch (Exception $e) {
            $responsable = ["status" => "error", "message" => $e->getMessage()];
        }
    } else {
        $responsable = ["status" => "error", "message" => "Por favor complete todos los campos requeridos."];
    }
}

echo json_encode($responsable);
?>

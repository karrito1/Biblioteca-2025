<?php
header("Content-Type: application/json; charset=utf-8");
include_once "../models/MySQL.php";

$baseDatos = new MySQL();
$baseDatos->conectar();

$responsable = ["status" => "error", "message" => "Solicitud invalida."];

// verificamos que sea una peticion post
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = intval($_POST["id"] ?? 0);

    if ($id > 0) {
        // Validar que el usuario exista
        $verificar = $baseDatos->efectuarConsulta("SELECT id FROM usuarios WHERE id = $id");
        if (mysqli_num_rows($verificar) > 0) {
            
            // Eliminar el usuario
            $sql = "DELETE FROM usuarios WHERE id = $id";
            if ($baseDatos->efectuarConsulta($sql)) {
                $responsable = [
                    "status" => "success",
                    "message" => "Usuario eliminado correctamente."
                ];
            } else {
                $responsable = [
                    "status" => "error",
                    "message" => "No se pudo eliminar el usuario."
                ];
            }

        } else {
            $responsable = [
                "status" => "error",
                "message" => "El usuario no existe."
            ];
        }

    } else {
        $responsable = [
            "status" => "error",
            "message" => "id no valido."
        ];
    }
}

$baseDatos->desconectar();
echo json_encode($responsable);

<?php
header("Content-Type: application/json; charset=utf-8");
require_once "../models/MySQL.php";

$mysql = new MySQL();
$mysql->conectar();

<<<<<<< HEAD
$response = ["status" => "error", "message" => "solicitud invalida."];
=======
$response = ["status" => "error", "message" => "Solicitud inválida."];
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validar campos obligatorios
    if (
        !empty($_POST["titulo"]) &&
        !empty($_POST["autor"]) &&
        !empty($_POST["isbn"]) &&
        !empty($_POST["categoria"]) &&
        !empty($_POST["cantidad"]) &&
        isset($_POST["disponibilidad"])
    ) {
        // Sanitizar y preparar datos
        $titulo         = htmlspecialchars(trim($_POST["titulo"]));
        $autor          = htmlspecialchars(trim($_POST["autor"]));
        $isbn           = htmlspecialchars(trim($_POST["isbn"]));
        $categoria      = htmlspecialchars(trim($_POST["categoria"]));
        $cantidad       = intval($_POST["cantidad"]);
        $disponibilidad = in_array($_POST["disponibilidad"], ["disponible", "prestado", "no disponible"])
            ? $_POST["disponibilidad"] : "disponible";
        $fecha          = !empty($_POST["fecha_registro"]) ? $_POST["fecha_registro"] : date("Y-m-d H:i:s");

        // Evitar duplicados por ISBN
        $checkQuery = "SELECT id FROM libros WHERE isbn = '$isbn'";
        $resultado = $mysql->efectuarConsulta($checkQuery);
        if ($resultado && $resultado->num_rows > 0) {
            $response = ["status" => "error", "message" => "Ya existe un libro con ese ISBN."];
        } else {
            // Insertar en la base de datos
            $insertar = "INSERT INTO libros (titulo, autor, isbn, categoria, cantidad, disponibilidad, fecha_registro) 
                         VALUES ('$titulo', '$autor', '$isbn', '$categoria', $cantidad, '$disponibilidad', '$fecha')";

            if ($mysql->efectuarConsulta($insertar)) {
                $response = ["status" => "success", "message" => "Libro registrado correctamente."];
            } else {
                $response = ["status" => "error", "message" => "Error al registrar libro: " . $mysql->getError()];
            }
        }
    } else {
        $response = ["status" => "error", "message" => "Por favor complete todos los campos obligatorios."];
    }
}

$mysql->desconectar();
echo json_encode($response);

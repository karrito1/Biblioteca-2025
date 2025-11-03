<?php
header("Content-Type: application/json; charset=utf-8");
require_once "../models/MySQL.php";

$mysql = new MySQL();
$mysql->conectar();

$response = ["status" => "error", "message" => "Error en solicitud"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        !empty($_POST["titulo"]) &&
        !empty($_POST["autor"]) &&
        !empty($_POST["isbn"]) &&
        !empty($_POST["categoria"]) &&
        !empty($_POST["cantidad"]) &&
        isset($_POST["disponibilidad"])
    ) {
        $titulo = htmlspecialchars(trim($_POST["titulo"]));
        $autor = htmlspecialchars(trim($_POST["autor"]));
        $isbn = htmlspecialchars(trim($_POST["isbn"]));
        $categoria = htmlspecialchars(trim($_POST["categoria"]));
        $cantidad = intval($_POST["cantidad"]);
        $disponibilidad = in_array($_POST["disponibilidad"], ["disponible", "prestado", "no disponible"]) 
            ? $_POST["disponibilidad"] : "disponible";
        $fecha = !empty($_POST["fecha_registro"]) ? $_POST["fecha_registro"] : date("Y-m-d H:i:s");

        $checkQuery = "SELECT id FROM libros WHERE isbn = '$isbn'";
        $resultado = $mysql->efectuarConsulta($checkQuery);
        if ($resultado && $resultado->num_rows > 0) {
            $response = ["status" => "error", "message" => "ISBN ya existe"];
        } else {
            $insertar = "INSERT INTO libros (titulo, autor, isbn, categoria, cantidad, disponibilidad, fecha_registro) 
                         VALUES ('$titulo', '$autor', '$isbn', '$categoria', $cantidad, '$disponibilidad', '$fecha')";

            if ($mysql->efectuarConsulta($insertar)) {
                $response = ["status" => "success", "message" => "Libro registrado"];
            } else {
                $response = ["status" => "error", "message" => "Error al registrar"];
            }
        }
    } else {
        $response = ["status" => "error", "message" => "Por favor complete todos los campos obligatorios."];
    }
}

$mysql->desconectar();
echo json_encode($response);

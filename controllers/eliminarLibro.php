<?php
header("Content-Type: application/json; charset=utf-8");
require_once("../models/MySQL.php");

$response = ["status" => "error", "message" => "Solicitud inválida."];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        $db = new MySQL();
        $db->conectar();

        // Eliminar libro
        $deleteQuery = "DELETE FROM libros WHERE id = $id";

        if ($db->efectuarConsulta($deleteQuery)) {
            $response = ["status" => "success", "message" => "Libro eliminado correctamente."];
        } else {
            $response = ["status" => "error", "message" => "No se pudo eliminar el libro: " . $db->getError()];
        }

        $db->desconectar();
    } else {
        $response = ["status" => "error", "message" => "ID de libro invalido."];
    }
}

echo json_encode($response);

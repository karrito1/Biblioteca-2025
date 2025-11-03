
<?php
header("Content-Type: application/json; charset=utf-8");
require_once("../models/MySQL.php");

$response = ["status" => "error", "message" => "Error en solicitud"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        !empty($_POST["id"]) &&
        !empty($_POST["titulo"]) &&
        !empty($_POST["autor"]) &&
        !empty($_POST["isbn"]) &&
        !empty($_POST["categoria"]) &&
        !empty($_POST["cantidad"]) &&
        isset($_POST["disponibilidad"])
    ) {
        $id = intval($_POST["id"]);
        $titulo = htmlspecialchars(trim($_POST["titulo"]));
        $autor = htmlspecialchars(trim($_POST["autor"]));
        $isbn = htmlspecialchars(trim($_POST["isbn"]));
        $categoria = htmlspecialchars(trim($_POST["categoria"]));
        $cantidad = intval($_POST["cantidad"]);
        $disponibilidad = in_array($_POST["disponibilidad"], ["Disponible", "No disponible"]) ? $_POST["disponibilidad"] : "Disponible";

        $db = new MySQL();
        $db->conectar();

        $updateQuery = "UPDATE libros SET 
            titulo='$titulo', 
            autor='$autor', 
            isbn='$isbn', 
            categoria='$categoria', 
            cantidad=$cantidad, 
            disponibilidad='$disponibilidad' 
            WHERE id=$id";

        if ($db->efectuarConsulta($updateQuery)) {
            $response = ["status" => "success", "message" => "Libro actualizado"];
        } else {
            $response = ["status" => "error", "message" => "Error al actualizar"];
        }

        $db->desconectar();
    } else {
        $response = ["status" => "error", "message" => "Complete todos los campos"];
    }
}

echo json_encode($response);

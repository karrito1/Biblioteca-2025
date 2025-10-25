<?php
header("Content-Type: application/json; charset=utf-8");
require_once "../models/MySQL.php";

$mysql = new MySQL();
$mysql->conectar();

$response = ["status" => "error", "message" => "Solicitud inválida."];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar que venga el ID
    if (!empty($_POST["id"])) {
        $id = intval($_POST["id"]);

        // Validar campos obligatorios
        if (
            !empty($_POST["titulo"]) &&
            !empty($_POST["autor"]) &&
            !empty($_POST["isbn"]) &&
            !empty($_POST["categoria"]) &&
            !empty($_POST["cantidad"]) &&
            isset($_POST["disponibilidad"])
        ) {
            // Sanitizar los datos
            $titulo         = htmlspecialchars(trim($_POST["titulo"]));
            $autor          = htmlspecialchars(trim($_POST["autor"]));
            $isbn           = htmlspecialchars(trim($_POST["isbn"]));
            $categoria      = htmlspecialchars(trim($_POST["categoria"]));
            $cantidad       = intval($_POST["cantidad"]);
            $disponibilidad = in_array($_POST["disponibilidad"], ["disponible", "prestado", "no disponible"])
                ? $_POST["disponibilidad"] : "disponible";

            // Verificar si el ISBN pertenece a otro libro
            $checkQuery = "SELECT id FROM libros WHERE isbn = '$isbn' AND id != $id";
            $resultado = $mysql->efectuarConsulta($checkQuery);
            if ($resultado && $resultado->num_rows > 0) {
                $response = ["status" => "error", "message" => "Ya existe otro libro con ese ISBN."];
            } else {
                // Actualizar datos
                $updateQuery = "
                    UPDATE libros 
                    SET 
                        titulo = '$titulo',
                        autor = '$autor',
                        isbn = '$isbn',
                        categoria = '$categoria',
                        cantidad = $cantidad,
                        disponibilidad = '$disponibilidad'
                    WHERE id = $id
                ";

                if ($mysql->efectuarConsulta($updateQuery)) {
                    $response = ["status" => "success", "message" => "Libro actualizado correctamente."];
                } else {
                    $response = ["status" => "error", "message" => "Error al actualizar el libro: " . $mysql->getError()];
                }
            }
        } else {
            $response = ["status" => "error", "message" => "Complete todos los campos obligatorios."];
        }
    } else {
        $response = ["status" => "error", "message" => "ID de libro no proporcionado."];
    }
}

$mysql->desconectar();
echo json_encode($response);

<?php
header("Content-Type: application/json; charset=utf-8");
require_once "../models/MySQL.php";

$baseDatos = new MySQL();
$baseDatos->conectar();

$respuesta = ["estado" => "error", "mensaje" => "Error en la solicitud"];

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    $id = intval($_GET["id"]);
    
    try {
        $consulta = "SELECT * FROM libros WHERE id = $id";
        $resultado = $baseDatos->efectuarConsulta($consulta);
        
        if ($libro = mysqli_fetch_assoc($resultado)) {
            $respuesta = [
                "estado" => "exito",
                "datos" => $libro
            ];
        } else {
            $respuesta = [
                "estado" => "error",
                "mensaje" => "Libro no encontrado"
            ];
        }
        
        $baseDatos->desconectar();
    } catch (Exception $e) {
        $respuesta = [
            "estado" => "error",
            "mensaje" => "Error en la base de datos: " . $e->getMessage()
        ];
    }
}

echo json_encode($respuesta);
?>
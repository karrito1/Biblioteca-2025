<?php
require_once(__DIR__ . "/../models/MySQL.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["id"]);
    $titulo = $_POST["titulo"];
    $autor = $_POST["autor"];
    $isbn = $_POST["isbn"];
    $categoria = $_POST["categoria"];
    $cantidad = intval($_POST["cantidad"]);
    $disponibilidad = $_POST["disponibilidad"];

    $baseDatos = new MySQL();
    $baseDatos->conectar();

    $query = "UPDATE libros 
              SET titulo = '$titulo', autor = '$autor', isbn = '$isbn', 
                  categoria = '$categoria', cantidad = $cantidad, 
                  disponibilidad = '$disponibilidad'
              WHERE id = $id";

    $resultado = $baseDatos->efectuarConsulta($query);
    $baseDatos->desconectar();

    if ($resultado) {
        header("Location: /views/libros/listaLibros.php?editado=1");
        exit();
    } else {
        echo "Error al actualizar el libro.";
    }
}
?>

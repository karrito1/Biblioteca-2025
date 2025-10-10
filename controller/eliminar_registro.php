<?php
header('Content-Type: application/json'); 

if (isset($_POST['nombre']) && isset($_POST['numeroDocumento'])) {
    include_once("../models/MySQL.php");
    $conexion = new MySQL();
    $conexion->conectar();

    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $numeroDocumento = htmlspecialchars(trim($_POST['numeroDocumento']));

    $sql_eliminar = "UPDATE empleados 
                     SET estado='INACTIVO' 
                     WHERE nombreEmpleado='$nombre' 
                       AND numeroDocumento='$numeroDocumento'";
    $resultado = $conexion->efectuarConsulta($sql_eliminar);

    $conexion->desconectar();

    if ($resultado) {
        echo json_encode(["carepingo" => "estovapasar", "message" => "Empleado marcado como INACTIVO"]);
    } else {
        echo json_encode(["carepingo" => "error", "message" => "No se pudo actualizar"]);
    }
    exit;
}

echo json_encode(["carepingo" => "error", "message" => "Datos incompletos"]);
exit;

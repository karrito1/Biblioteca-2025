<?php
include_once "./models/MySQL.php";

$mysql = new MySQL();
$mysql->conectar();

$query = "
    SELECT c.nombreCargo AS cargo, COUNT(e.idempleados) AS total_empleados
    FROM empleados e
    INNER JOIN cargo c ON e.cargo_idcargo = c.idcargo
    GROUP BY c.nombreCargo
";

$resultado = $mysql->efectuarConsulta($query);

$datos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $datos[] = $fila;
}

$mysql->desconectar();

header('Content-Type: application/json');
echo json_encode($datos);

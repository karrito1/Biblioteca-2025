<?php
include_once "./models/MySQL.php";

$mysql = new MySQL();
$mysql->conectar();

$query = "
    SELECT d.nombreDepartamento, COUNT(e.idempleados) as total
    FROM empleados e
    INNER JOIN departamento d ON e.departamento_iddepartamento = d.iddepartamento
    GROUP BY d.nombreDepartamento
";
$resultado = $mysql->efectuarConsulta($query);

$data = [];
while ($row = mysqli_fetch_assoc($resultado)) {
    $data[] = $row;
}

$mysql->desconectar();

header('Content-Type: application/json');
echo json_encode($data);
?>


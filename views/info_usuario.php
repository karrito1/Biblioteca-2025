<?php
include_once "../models/MySQL.php";

class info_usuario
{
    private $db;

    public function __construct()
    {
        $this->db = new MySQL();
        $this->db->conectar();
    }

    public function getAllEmpleados()
    {
        $consulta = "
            SELECT 
                e.idempleados AS id,
                e.nombreEmpleado AS nombre,
                e.numeroDocumento AS documento,
                e.fechaIngreso AS fecha_ingreso,
                e.correo AS correo,
                e.telefono AS telefono,
                e.salario AS salario,
                e.estado AS estado,
                c.nombreCargo AS cargo,
                d.nombreDepartamento AS departamento,
                e.imagen AS imagen
            FROM empleados e
            LEFT JOIN cargo c ON e.cargo_idcargo = c.idcargo
            LEFT JOIN departamento d ON e.departamento_iddepartamento = d.iddepartamento
        ";
        $resultado = $this->db->efectuarConsulta($consulta);

        $empleados = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $empleados[] = $fila;
        }
        return $empleados;
    }

    public function __destruct()
    {
        $this->db->desconectar();
    }
}


$info = new info_usuario();
$empleados = $info->getAllEmpleados();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DATOS EMPLEADOS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/068a4d5189.js" crossorigin="anonymous"></script>
</head>

<body>
    <h1 class="text-center my-3">DATOS EMPLEADOS</h1>
    <div class="container-fluid">
        <div class="table-responsive">
            <table class="table table-sm table-bordered" style="font-size: 0.85rem;">
                <thead class="table-info">
                    <tr>
                        <th>ID EMPLEADOS</th>
                        <th>CARGO</th>
                        <th>AREA</th>
                        <th>NOMBRE COMPLETO</th>
                        <th>NUMERO DOCUMENTO</th>
                        <th>FECHA INGRESO</th>
                        <th>SALARIO</th>
                        <th>CORREO</th>
                        <th>TELEFONO</th>
                        <th>ESTADO</th>
                        <th>IMAGEN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empleados as $fila): ?>
                        <tr>
                            <td><?= $fila['id'] ?></td>
                            <td><?= $fila['cargo'] ?></td>
                            <td><?= $fila['departamento'] ?></td>
                            <td><?= $fila['nombre'] ?></td>
                            <td><?= $fila['documento'] ?></td>
                            <td><?= $fila['fecha_ingreso'] ?></td>
                            <td><?= $fila['salario'] ?></td>
                            <td><?= $fila['correo'] ?></td>
                            <td><?= $fila['telefono'] ?></td>
                            <td><?= $fila['estado'] ?></td>
                            <td>
                                <?php if (!empty($fila['imagen'])): ?>
                                    <img src="../<?= htmlspecialchars($fila['imagen']) ?>" width="60" height="60" style="object-fit: cover; border-radius: 5px;">
                                <?php else: ?>
                                    Sin imagen
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
include_once "../models/MySQL.php";
class InfoUsuario
{
    private $db;

    public function __construct()
    {
        $this->db = new MySQL();
        $this->db->conectar();
    }

    public function getAllEmpleados($idDepartamento = null)
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

        if ($idDepartamento !== null) {
            $consulta .= " WHERE e.departamento_iddepartamento = " . intval($idDepartamento);
        }

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

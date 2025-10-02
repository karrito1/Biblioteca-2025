<?php
class MySQL
{
    private $ipServidor = "localhost";
    private $usuarioBase = "root";
    private $contrasena = "";
    private $nombreBaseDatos = "bibliotecaProyecto";
    private $conexion;
    public function conectar()
    {
        $this->conexion = mysqli_connect($this->ipServidor, $this->usuarioBase, $this->contrasena, $this->nombreBaseDatos);
        if (!$this->conexion) {
            die("Error al conectar a la base de datos: " . mysqli_connect_error());
        }
        mysqli_set_charset($this->conexion, "utf8");
    }
    public function desconectar()
    {
        if ($this->conexion) {
            mysqli_close($this->conexion);
        }
    }
    public function efectuarConsulta($consulta)
    {
        mysqli_query($this->conexion, "SET NAMES 'utf8'");
        mysqli_query($this->conexion, "SET CHARACTER SET 'utf8'");
        $resultado = mysqli_query($this->conexion, $consulta);
        if (!$resultado) {
            echo "Error en la consulta: " . mysqli_error($this->conexion);
        }
        return $resultado;
    }
    public function getError()
    {
        return mysqli_error($this->conexion);
    }
}

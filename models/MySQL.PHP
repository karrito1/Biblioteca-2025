<?php
class MySQL
{
    private $ipServidor = "127.0.0.1";
    private $usuarioBase = "root";
    private $contrasena = "";
    private $nombreBaseDatos = "Biblioteca-2025";

    private $conexion;

    // Conectar a la base de datos y devolver la conexión
    public function conectar()
    {
        $this->conexion = mysqli_connect(
            $this->ipServidor,
            $this->usuarioBase,
            $this->contrasena,
            $this->nombreBaseDatos
        );

        if (!$this->conexion) {
            die("Error al conectar a la base de datos: " . mysqli_connect_error());
        }

        mysqli_set_charset($this->conexion, "utf8");

        return $this->conexion;
    }

    // Desconectar
    public function desconectar()
    {
        if ($this->conexion) {   // Verifica que exista la conexion
            $this->conexion->close();
            $this->conexion = null; // Evita cerrarla de nuevo
        }
    }

    // Ejecutar consulta simple (no preparada)
    public function efectuarConsulta($consulta)
    {
        $resultado = mysqli_query($this->conexion, $consulta);

        if (!$resultado) {
            throw new Exception("Error en la consulta: " . mysqli_error($this->conexion));
        }

        return $resultado;
    }

    // Preparar consultas seguras 
    public function preparar($consulta)
    {
        return $this->conexion->prepare($consulta);
    }

    // Obtener error
    public function getError()
    {
        return mysqli_error($this->conexion);
    }
}

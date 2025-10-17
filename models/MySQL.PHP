<?php
class MySQL
{
    private $ipServidor = "bse0zhzg1wcspyzagqyl-mysql.services.clever-cloud.com";
    private $usuarioBase = "umlfpespdjrybek5";
    private $contrasena = "Y9bK2DzJVwFRgQSWUVys";
    private $nombreBaseDatos = "bse0zhzg1wcspyzagqyl";

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
        if ($this->conexion) {   // Verifica que exista la conexión
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

    // Preparar consultas seguras (prepared statements)
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

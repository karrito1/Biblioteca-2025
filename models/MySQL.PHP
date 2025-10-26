<?php
<<<<<<< HEAD
class MySQL {
    private $config = [
        'host' => "127.0.0.1",
        'user' => "root", 
        'pass' => "",
        'db' => "bzicjjkgjvuapbcqumts"
    ];
    private $conexion;

    public function conectar() {
        $this->conexion = mysqli_connect(
            $this->config['host'],
            $this->config['user'], 
            $this->config['pass'],
            $this->config['db']
        );

        if (!$this->conexion) {
            die("Error al conectar: " . mysqli_connect_error());
        }

        mysqli_set_charset($this->conexion, "utf8");
        return $this->conexion;
    }

    public function desconectar() {
        if ($this->conexion) {
            $this->conexion->close();
            $this->conexion = null;
        }
    }

    public function efectuarConsulta($consulta)
    {
        if (!$this->conexion) {
            $this->conectar();
        }
        
=======
class MySQL
{
    private $ipServidor = "127.0.0.1";
    private $usuarioBase = "root";
    private $contrasena = "";
    private $nombreBaseDatos = "biblioteca";

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
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
        $resultado = mysqli_query($this->conexion, $consulta);

        if (!$resultado) {
            throw new Exception("Error en la consulta: " . mysqli_error($this->conexion));
        }

        return $resultado;
    }
<<<<<<< HEAD

    public function preparar($consulta)
    {
        if (!$this->conexion) {
            $this->conectar();
        }
=======
    public function preparar($consulta)
    {
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
        return $this->conexion->prepare($consulta);
    }

    // Obtener error
    public function getError()
    {
        return mysqli_error($this->conexion);
    }
}
<<<<<<< HEAD
=======
?>

>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264

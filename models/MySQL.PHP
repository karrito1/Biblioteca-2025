<?php
class MySQL {
    private $config = [
        'host' => "127.0.0.1",
        'user' => "root", 
        'pass' => "",
        'db' => "biblioteca20250"
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
        
        $resultado = mysqli_query($this->conexion, $consulta);

        if (!$resultado) {
            throw new Exception("Error en la consulta: " . mysqli_error($this->conexion));
        }

        return $resultado;
    }

    public function preparar($consulta)
    {
        if (!$this->conexion) {
            $this->conectar();
        }
        return $this->conexion->prepare($consulta);
    }

    // Obtener error
    public function getError()
    {
        return mysqli_error($this->conexion);
    }
}

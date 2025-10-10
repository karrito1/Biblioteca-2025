<?php
header("Content-Type: application/json; charset=utf-8");
include_once "../models/MySQL.php";
$mysql = new MySQL();
$mysql->conectar();

$response = ["status" => "error", "message" => "Solicitud inválida."];
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        !empty($_POST["nombre"]) &&
        !empty($_POST["numeroDocumento"]) &&
        !empty($_POST["cargo"]) &&
        !empty($_POST["departamento"]) &&
        !empty($_POST["fechaIngreso"]) &&
        !empty($_POST["salario"]) &&
        !empty($_POST["telefono"]) &&
        !empty($_POST["correo"]) &&
        !empty($_POST["password"]) &&
        !empty($_POST["estado"])
    ) {

        $nombre          = htmlspecialchars(trim($_POST["nombre"]));
        $numeroDocumento = htmlspecialchars(trim($_POST["numeroDocumento"]));
        $cargo           = (int) $_POST["cargo"];
        $departamento    = (int) $_POST["departamento"];
        $fechaIngreso    = htmlspecialchars($_POST["fechaIngreso"]);
        $salario         = (int) $_POST["salario"];
        $telefono        = htmlspecialchars(trim($_POST["telefono"]));
        $correo          = filter_var(trim($_POST["correo"]), FILTER_SANITIZE_EMAIL);
        $estado          = htmlspecialchars(trim($_POST["estado"]));
        $passwordPlano   = $_POST["password"];
        $hash            = password_hash($passwordPlano, PASSWORD_BCRYPT);

        // Manejo de imagen
        $imagenRuta = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $permitidos = ['image/jpeg' => '.jpg', 'image/png' => '.png'];
            $tipo = mime_content_type($_FILES['imagen']['tmp_name']);

            if (!array_key_exists($tipo, $permitidos)) {
                echo json_encode(["status" => "error", "message" => "Solo se permiten imágenes JPG y PNG."]);
                exit;
            }

            $ext = $permitidos[$tipo];
            $nombreUnico = 'imagen_' . date('Ymd_Hisv') . $ext;
            $ruta = 'assets/img/' . $nombreUnico;
            $rutaAbsoluta = __DIR__ . '/../' . $ruta;

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaAbsoluta)) {
                echo json_encode(["status" => "error", "message" => "Error al subir la imagen."]);
                exit;
            }

            $imagenRuta = $ruta;
        }

        // InsertAR  en la tabla empleados
        $insertar = "INSERT INTO empleados 
            (cargo_idcargo, departamento_iddepartamento, nombreEmpleado, numeroDocumento, fechaIngreso, salario, telefono, correo, passwordd, estado, imagen) 
            VALUES 
            ('$cargo', '$departamento', '$nombre', '$numeroDocumento', '$fechaIngreso', '$salario', '$telefono', '$correo', '$hash', '$estado', " . ($imagenRuta ? "'$imagenRuta'" : "NULL") . ")";

        if ($mysql->efectuarConsulta($insertar)) {
            $response = ["status" => "success", "message" => "Registro insertado correctamente."];
        } else {
            $response = ["status" => "error", "message" => "Error en INSERT: " . $mysql->getError()];
        }
    } else {
        $response = ["status" => "error", "message" => "Por favor complete todos los campos."];
    }
}

$mysql->desconectar();
echo json_encode($response);

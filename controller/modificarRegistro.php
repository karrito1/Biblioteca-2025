<?php
header('Content-Type: application/json');

$response = ["status" => "error", "message" => ""];

if (!empty($_POST["idempleados"])) {
    if (
        !empty($_POST["nombreEmpleado"]) &&
        !empty($_POST["numeroDocumento"]) &&
        !empty($_POST["cargo_idcargo"]) &&
        !empty($_POST["departamento_iddepartamento"]) &&
        !empty($_POST["fechaIngreso"]) &&
        !empty($_POST["salario"]) &&
        !empty($_POST["telefono"]) &&
        !empty($_POST["correo"])
    ) {
        $idempleados                 = (int) $_POST["idempleados"];
        $nombre                      = htmlspecialchars(trim($_POST["nombreEmpleado"]));
        $numeroDocumento             = htmlspecialchars(trim($_POST["numeroDocumento"]));
        $cargo_idcargo               = (int) $_POST["cargo_idcargo"];
        $departamento_iddepartamento = (int) $_POST["departamento_iddepartamento"];
        $fechaIngreso                = htmlspecialchars($_POST["fechaIngreso"]);
        $salario                     = (int) $_POST["salario"];
        $telefono                    = htmlspecialchars(trim($_POST["telefono"]));
        $correo                      = filter_var(trim($_POST["correo"]), FILTER_SANITIZE_EMAIL);

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

        include_once "../models/MySQL.php";
        $mysql = new MySQL();
        $mysql->conectar();

        if ($imagenRuta) {
            $consulta = "UPDATE empleados SET 
                nombreEmpleado = '$nombre',
                numeroDocumento = '$numeroDocumento',
                cargo_idcargo = $cargo_idcargo,
                departamento_iddepartamento = $departamento_iddepartamento,
                fechaIngreso = '$fechaIngreso',
                salario = $salario,
                telefono = '$telefono',
                correo = '$correo',
                imagen = '$imagenRuta'
                WHERE idempleados = $idempleados";
        } else {
            $consulta = "UPDATE empleados SET 
                nombreEmpleado = '$nombre',
                numeroDocumento = '$numeroDocumento',
                cargo_idcargo = $cargo_idcargo,
                departamento_iddepartamento = $departamento_iddepartamento,
                fechaIngreso = '$fechaIngreso',
                salario = $salario,
                telefono = '$telefono',
                correo = '$correo'
                WHERE idempleados = $idempleados";
        }

        if ($mysql->efectuarConsulta($consulta)) {
            $response = ["status" => "ok", "message" => " Registro actualizado correctamente."];
        } else {
            $response = ["status" => "error", "message" => "Error al actualizar el registro."];
        }

        $mysql->desconectar();
    } else {
        $response = ["status" => "error", "message" => "Todos los campos son obligatorios."];
    }
} else {
    $response = ["status" => "error", "message" => "Falta el ID del empleado."];
}

echo json_encode($response);

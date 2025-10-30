<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once("../models/MySQL.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baseDatos = new MySQL();
    $conexion = $baseDatos->conectar();

    $libro_id = intval($_POST['libro_id']);
    $usuario_id = intval($_POST['usuario_id']);
    $fecha_prestamo = $_POST['fecha_prestamo'];
    $fecha_devolucion = $_POST['fecha_devolucion'];
    $observaciones = $_POST['observaciones'] ?? '';

    // Verificar disponibilidad
    $queryDisp = "SELECT disponibilidad, titulo FROM libros WHERE id = $libro_id";
    $resultDisp = $baseDatos->efectuarConsulta($queryDisp);
    $libro = mysqli_fetch_assoc($resultDisp);

    if (!$libro || $libro['disponibilidad'] !== 'disponible') {
        echo json_encode(['success' => false, 'message' => 'Libro no disponible']);
        exit;
    }

    // Registrar prestamo
    $queryPrestamo = "INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo, fecha_devolucion, estado) 
                      VALUES ($usuario_id, $libro_id, '$fecha_prestamo', '$fecha_devolucion', 'activo')";

    if ($baseDatos->efectuarConsulta($queryPrestamo)) {
        // Marcar libro como prestado
        $queryUpdate = "UPDATE libros SET disponibilidad = 'prestado' WHERE id = $libro_id";
        $baseDatos->efectuarConsulta($queryUpdate);

        // Obtener correo y nombre del usuario
        $queryCorreo = "SELECT nombre, email FROM usuarios WHERE id = $usuario_id";
        $resultCorreo = $baseDatos->efectuarConsulta($queryCorreo);
        $usuario = mysqli_fetch_assoc($resultCorreo);
        $correoUsuario = $usuario['email'];
        $nombreUsuario = $usuario['nombre'];

        // Enviar correo
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tucorreo@gmail.com'; // tu correo
            $mail->Password   = 'tu_contrasena_de_aplicacion'; // clave de aplicacion
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('tucorreo@gmail.com', 'Biblioteca 2025');
            $mail->addAddress($correoUsuario, $nombreUsuario);

            $mail->isHTML(true);
            $mail->Subject = 'Notificacion de prestamo';
            $mail->Body    = "
                <h2>Hola, $nombreUsuario</h2>
                <p>Tu prestamo del libro <strong>{$libro['titulo']}</strong> ha sido registrado correctamente.</p>
                <p><strong>Fecha de prestamo:</strong> $fecha_prestamo</p>
                <p><strong>Fecha de devolucion:</strong> $fecha_devolucion</p>
                <p>Gracias por usar la Biblioteca 2025!</p>
            ";

            $mail->send();
            echo json_encode(['success' => true, 'message' => 'Prestamo registrado y correo enviado.']);
        } catch (Exception $e) {
            echo json_encode(['success' => true, 'message' => 'Prestamo registrado, pero error al enviar correo: ' . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar el prestamo']);
    }

    $baseDatos->desconectar();
}

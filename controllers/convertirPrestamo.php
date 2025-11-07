<?php
session_start();
require_once("../models/MySQL.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

header('Content-Type: application/json');

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Validar sesion y rol
if (!isset($_SESSION['usuario_id']) || strtoupper(trim($_SESSION['roles'])) !== 'ADMINISTRADOR') {
    echo json_encode(['success' => false, 'message' => 'No tiene permisos']);
    exit;
}

// Validar ID de reserva
$reserva_id = isset($_POST['reserva_id']) ? (int)$_POST['reserva_id'] : 0;
if ($reserva_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de reserva no valido']);
    exit;
}

// Obtener datos de la reserva aprobada
$sql = "SELECT r.usuario_id, r.libro_id, r.fecha_reserva, u.email, u.nombre, l.titulo
        FROM reservas r
        INNER JOIN usuarios u ON r.usuario_id = u.id
        INNER JOIN libros l ON r.libro_id = l.id
        WHERE r.id=$reserva_id AND r.estado='aprobada'";

$result = mysqli_query($conexion, $sql);
$reserva = mysqli_fetch_assoc($result);

if (!$reserva) {
    echo json_encode(['success' => false, 'message' => 'Reserva no encontrada o no aprobada']);
    exit;
}

$usuario_id = $reserva['usuario_id'];
$libro_id = $reserva['libro_id'];
$tituloLibro = $reserva['titulo'];
$nombreUsuario = $reserva['nombre'];
$correoUsuario = $reserva['email'];

$fecha_prestamo = date('Y-m-d');
$fecha_devolucion = date('Y-m-d', strtotime('+7 days'));
$estado = 'activo';

// Insertar en tabla prestamos
$sql_insert = "INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo, fecha_devolucion, estado) 
               VALUES ($usuario_id, $libro_id, '$fecha_prestamo', '$fecha_devolucion', '$estado')";

if (mysqli_query($conexion, $sql_insert)) {

    // Actualizar estado de reserva y disponibilidad del libro
    mysqli_query($conexion, "UPDATE reservas SET estado='convertida' WHERE id=$reserva_id");
    mysqli_query($conexion, "UPDATE libros SET disponibilidad='prestado' WHERE id=$libro_id");

    // Enviar correo
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'senabibiloteca73@gmail.com';
        $mail->Password   = 'xkux owcf zrli yump';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('senabibiloteca73@gmail.com', 'Biblioteca 2025');
        $mail->addAddress($correoUsuario, $nombreUsuario);

        $mail->isHTML(true);
        $mail->Subject = 'Prestamo creado a partir de reserva';
        $mail->Body    = "
            <h2>Hola, $nombreUsuario</h2>
            <p>Tu reserva ha sido convertida en préstamo correctamente.</p>
            <p><strong>Libro:</strong> $tituloLibro</p>
            <p><strong>Fecha de prestamo:</strong> $fecha_prestamo</p>
            <p><strong>Fecha de devolucion:</strong> $fecha_devolucion</p>
            <p>Gracias por utilizar la Biblioteca!</p>
        ";

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Reserva convertida en prestamo y correo enviado']);
    } catch (Exception $e) {
        echo json_encode(['success' => true, 'message' => 'Prestamo creado, pero error al enviar correo']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error al crear el prestamo']);
}

$baseDatos->desconectar();

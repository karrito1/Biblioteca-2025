<?php
require_once __DIR__ . "/../models/MySQL.php";

session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['roles'] !== 'ADMINISTRADOR') {
    header("Location: ../index.php?error=1");
    exit();
}

$baseDatos = new MySQL();
$baseDatos->conectar();

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$formato = isset($_GET['formato']) ? $_GET['formato'] : 'excel';

if ($formato === 'excel') {
    generarExcel($baseDatos, $tipo);
} elseif ($formato === 'pdf') {
    generarPDF($baseDatos, $tipo);
}

function generarExcel($baseDatos, $tipo) {
    $datos = [];
    
    if ($tipo === 'usuarios') {
        $consulta = "SELECT id, nombre, email, telefono, estado FROM usuarios ORDER BY nombre ASC";
        $resultado = $baseDatos->efectuarConsulta($consulta);
        
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=reporte_usuarios.xls");
        
        echo "ID\tNombre\tEmail\tTelefono\tEstado\n";
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo $fila['id'] . "\t" . $fila['nombre'] . "\t" . $fila['email'] . "\t" . $fila['telefono'] . "\t" . $fila['estado'] . "\n";
        }
    } elseif ($tipo === 'libros') {
        $consulta = "SELECT id, titulo, autor, isbn, cantidad FROM libros ORDER BY titulo ASC";
        $resultado = $baseDatos->efectuarConsulta($consulta);
        
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=reporte_libros.xls");
        
        echo "ID\tTitulo\tAutor\tISBN\tCantidad\n";
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo $fila['id'] . "\t" . $fila['titulo'] . "\t" . $fila['autor'] . "\t" . $fila['isbn'] . "\t" . $fila['cantidad'] . "\n";
        }
    } elseif ($tipo === 'prestamos') {
        $consulta = "SELECT p.id, u.nombre as usuario, l.titulo as libro, p.fecha_prestamo, p.fecha_devolucion, p.estado 
                     FROM prestamos p 
                     JOIN usuarios u ON p.usuario_id = u.id 
                     JOIN libros l ON p.libro_id = l.id 
                     ORDER BY p.fecha_prestamo DESC";
        $resultado = $baseDatos->efectuarConsulta($consulta);
        
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=reporte_prestamos.xls");
        
        echo "ID\tUsuario\tLibro\tFecha Prestamo\tFecha Devolucion\tEstado\n";
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo $fila['id'] . "\t" . $fila['usuario'] . "\t" . $fila['libro'] . "\t" . $fila['fecha_prestamo'] . "\t" . $fila['fecha_devolucion'] . "\t" . $fila['estado'] . "\n";
        }
    }
    
    exit();
}

function generarPDF($baseDatos, $tipo) {
    require __DIR__ . "/../libreria/fpdf186/fpdf.php";
    
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont("Arial", "B", 14);
    
    if ($tipo === 'usuarios') {
        $pdf->Cell(0, 10, "Reporte de Usuarios", 0, 1, "C");
        
        $consulta = "SELECT id, nombre, email, telefono, estado FROM usuarios ORDER BY nombre ASC";
        $resultado = $baseDatos->efectuarConsulta($consulta);
        
        $pdf->SetFont("Arial", "B", 9);
        $pdf->Cell(15, 7, "ID", 1);
        $pdf->Cell(40, 7, "Nombre", 1);
        $pdf->Cell(50, 7, "Email", 1);
        $pdf->Cell(30, 7, "Telefono", 1);
        $pdf->Cell(15, 7, "Estado", 1);
        $pdf->Ln();
        
        $pdf->SetFont("Arial", "", 8);
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $pdf->Cell(15, 6, $fila['id'], 1);
            $pdf->Cell(40, 6, mb_substr($fila['nombre'], 0, 20), 1);
            $pdf->Cell(50, 6, mb_substr($fila['email'], 0, 24), 1);
            $pdf->Cell(30, 6, $fila['telefono'], 1);
            $pdf->Cell(15, 6, $fila['estado'], 1);
            $pdf->Ln();
        }
        
        $pdf->Output("D", "reporte_usuarios.pdf");
        
    } elseif ($tipo === 'libros') {
        $pdf->Cell(0, 10, "Reporte de Libros", 0, 1, "C");
        
        $consulta = "SELECT id, titulo, autor, isbn, cantidad FROM libros ORDER BY titulo ASC";
        $resultado = $baseDatos->efectuarConsulta($consulta);
        
        $pdf->SetFont("Arial", "B", 9);
        $pdf->Cell(15, 7, "ID", 1);
        $pdf->Cell(50, 7, "Titulo", 1);
        $pdf->Cell(40, 7, "Autor", 1);
        $pdf->Cell(30, 7, "ISBN", 1);
        $pdf->Cell(15, 7, "Cant", 1);
        $pdf->Ln();
        
        $pdf->SetFont("Arial", "", 8);
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $pdf->Cell(15, 6, $fila['id'], 1);
            $pdf->Cell(50, 6, mb_substr($fila['titulo'], 0, 24), 1);
            $pdf->Cell(40, 6, mb_substr($fila['autor'], 0, 20), 1);
            $pdf->Cell(30, 6, $fila['isbn'], 1);
            $pdf->Cell(15, 6, $fila['cantidad'], 1);
            $pdf->Ln();
        }
        
        $pdf->Output("D", "reporte_libros.pdf");
        
    } elseif ($tipo === 'prestamos') {
        $pdf->Cell(0, 10, "Reporte de Prestamos", 0, 1, "C");
        
        $consulta = "SELECT p.id, u.nombre as usuario, l.titulo as libro, p.fecha_prestamo, p.fecha_devolucion, p.estado 
                     FROM prestamos p 
                     JOIN usuarios u ON p.usuario_id = u.id 
                     JOIN libros l ON p.libro_id = l.id 
                     ORDER BY p.fecha_prestamo DESC";
        $resultado = $baseDatos->efectuarConsulta($consulta);
        
        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(12, 7, "ID", 1);
        $pdf->Cell(30, 7, "Usuario", 1);
        $pdf->Cell(35, 7, "Libro", 1);
        $pdf->Cell(25, 7, "Fecha Prest", 1);
        $pdf->Cell(25, 7, "Fecha Devol", 1);
        $pdf->Cell(18, 7, "Estado", 1);
        $pdf->Ln();
        
        $pdf->SetFont("Arial", "", 7);
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $pdf->Cell(12, 6, $fila['id'], 1);
            $pdf->Cell(30, 6, mb_substr($fila['usuario'], 0, 14), 1);
            $pdf->Cell(35, 6, mb_substr($fila['libro'], 0, 16), 1);
            $pdf->Cell(25, 6, $fila['fecha_prestamo'], 1);
            $pdf->Cell(25, 6, $fila['fecha_devolucion'], 1);
            $pdf->Cell(18, 6, $fila['estado'], 1);
            $pdf->Ln();
        }
        
        $pdf->Output("D", "reporte_prestamos.pdf");
    }
    
    exit();
}

$baseDatos->desconectar();
?>

<?php
ob_start();
require_once '../libreria/fpdf186/fpdf.php';
require_once '../models/MySQL.php';

if (!empty($_GET['tipo'])) {
    $tipo = $_GET['tipo'];
    $bd = new MySQL();
    $conexion = $bd->conectar();

    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial','B',14);
            $this->Cell(0,10,'Reporte Biblioteca SENAP',0,1,'C');
            $this->Ln(5);
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial','I',8);
            $this->Cell(0,10,'pagina '.$this->PageNo(),0,0,'C');
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',9);

    switch($tipo) {
        case 'prestamos':
            // encabezados
            $pdf->Cell(15,8,'ID',1);
            $pdf->Cell(40,8,'Usuario',1);
            $pdf->Cell(45,8,'Libro',1);
            $pdf->Cell(25,8,'F.Prestamo',1);
            $pdf->Cell(25,8,'F.Devolucion',1);
            $pdf->Cell(20,8,'Estado',1);
            $pdf->Ln();

            $pdf->SetFont('Arial','',8);
            $consulta = "SELECT p.*, u.nombre as usuario_nombre, l.titulo as libro_titulo 
                        FROM prestamos p 
                        INNER JOIN usuarios u ON p.usuario_id = u.id 
                        INNER JOIN libros l ON p.libro_id = l.id 
                        ORDER BY p.fecha_prestamo DESC";
            $resultado = $bd->efectuarConsulta($consulta);

            while($fila = mysqli_fetch_assoc($resultado)) {
                $pdf->Cell(15,8,$fila['id'],1);
                $pdf->Cell(40,8,substr($fila['usuario_nombre'],0,25),1);
                $pdf->Cell(45,8,substr($fila['libro_titulo'],0,30),1);
                $pdf->Cell(25,8,date('d/m/Y',strtotime($fila['fecha_prestamo'])),1);
                $pdf->Cell(25,8,date('d/m/Y',strtotime($fila['fecha_devolucion'])),1);
                $pdf->Cell(20,8,$fila['estado'],1);
                $pdf->Ln();
            }
            break;

        case 'usuarios':
            // encabezados
            $pdf->Cell(15,8,'ID',1);
            $pdf->Cell(50,8,'Nombre',1);
            $pdf->Cell(60,8,'Email',1);
            $pdf->Cell(30,8,'Telefono',1);
            $pdf->Cell(25,8,'Rol',1);
            $pdf->Ln();

            $pdf->SetFont('Arial','',8);
            $consulta = "SELECT * FROM usuarios ORDER BY nombre";
            $resultado = $bd->efectuarConsulta($consulta);

            while($fila = mysqli_fetch_assoc($resultado)) {
                $pdf->Cell(15,8,$fila['id'],1);
                $pdf->Cell(50,8,substr($fila['nombre'],0,30),1);
                $pdf->Cell(60,8,substr($fila['email'],0,35),1);
                $pdf->Cell(30,8,$fila['telefono'],1);
                $pdf->Cell(25,8,$fila['Roles'],1);
                $pdf->Ln();
            }
            break;

        case 'libros':
            // encabezados
            $pdf->Cell(15,8,'ID',1);
            $pdf->Cell(55,8,'Titulo',1);
            $pdf->Cell(45,8,'Autor',1);
            $pdf->Cell(25,8,'ISBN',1);
            $pdf->Cell(20,8,'Cantidad',1);
            $pdf->Cell(20,8,'Estado',1);
            $pdf->Ln();

            $pdf->SetFont('Arial','',8);
            $consulta = "SELECT * FROM libros ORDER BY titulo";
            $resultado = $bd->efectuarConsulta($consulta);

            while($fila = mysqli_fetch_assoc($resultado)) {
                $pdf->Cell(15,8,$fila['id'],1);
                $pdf->Cell(55,8,substr($fila['titulo'],0,35),1);
                $pdf->Cell(45,8,substr($fila['autor'],0,25),1);
                $pdf->Cell(25,8,$fila['isbn'],1);
                $pdf->Cell(20,8,$fila['cantidad'],1);
                $pdf->Cell(20,8,$fila['disponibilidad'],1);
                $pdf->Ln();
            }
            break;
    }

    $pdf->Output('I','Reporte_'.ucfirst($tipo).'.pdf');
    $bd->desconectar();
}

ob_end_flush();
exit;
?>
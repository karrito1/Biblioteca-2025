<?php
ob_start();
require_once '../libs/fpdf186/fpdf.php';
require_once '../controller/info_usuario.php';

if (!empty($_POST['departamento'])) {
    $idDepartamento = (int) $_POST['departamento'];

    $controller = new InfoUsuario();
    $empleados = $controller->getAllEmpleados($idDepartamento);

    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, 'Empleados por Departamento', 0, 1, 'C');
            $this->Ln(5);
        }
        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 9);

    // Encabezados
    $pdf->Cell(10, 8, 'ID', 1);
    $pdf->Cell(30, 8, 'Nombre', 1);
    $pdf->Cell(25, 8, 'Documento', 1);
    $pdf->Cell(30, 8, 'Correo', 1);
    $pdf->Cell(20, 8, 'Telefono', 1);
    $pdf->Cell(20, 8, 'Salario', 1);
    $pdf->Cell(25, 8, 'Cargo', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 9);

    foreach ($empleados as $emp) {
        $pdf->Cell(10, 8, $emp['id'], 1);
        $pdf->Cell(30, 8, $emp['nombre'], 1);
        $pdf->Cell(25, 8, $emp['documento'], 1);
        $pdf->Cell(30, 8, $emp['correo'], 1);
        $pdf->Cell(20, 8, $emp['telefono'], 1);
        $pdf->Cell(20, 8, $emp['salario'], 1);
        $pdf->Cell(25, 8, $emp['cargo'], 1);
        $pdf->Ln();
    }

    $pdf->Output('I', 'Empleados_Departamento.pdf');
}

ob_end_flush();
exit;

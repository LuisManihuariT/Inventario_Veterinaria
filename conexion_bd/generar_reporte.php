<?php
require_once("conexion.php");
require_once("../fpdf/fpdf.php"); // Librería FPDF
// reporte de inventario de productos
class PDF extends FPDF
{
    // Encabezado del reporte
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Reporte de Inventario', 0, 1, 'C');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(20, 10, 'ID', 1);
        $this->Cell(40, 10, 'Nombre', 1);
        $this->Cell(60, 10, 'Descripcion', 1);
        $this->Cell(30, 10, 'Stock', 1);
        $this->Cell(40, 10, 'Precio Venta', 1);
        $this->Ln();
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Crear el PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Consultar productos
$stmt = $pdo->prepare("SELECT * FROM producto");
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agregar datos al reporte
foreach ($productos as $producto) {
    $pdf->Cell(20, 10, $producto['ID_Producto'], 1);
    $pdf->Cell(40, 10, $producto['Nombre'], 1);
    $pdf->Cell(60, 10, $producto['Descripcion'], 1);
    $pdf->Cell(30, 10, $producto['Stock_Actual'], 1);
    $pdf->Cell(40, 10, $producto['Precio_Venta'], 1);
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output('D', 'Reporte_Inventario.pdf'); // Descargar automáticamente

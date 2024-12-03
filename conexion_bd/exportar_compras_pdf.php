<?php
require '../fpdf/fpdf.php';
require_once("../conexion_bd/reporte_compras_proveedor.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $resultados = obtenerComprasPorProveedor($fecha_inicio, $fecha_fin);

    class PDF extends FPDF
    {
        // Encabezado
        function Header()
        {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, 'Reporte de Compras por Proveedor', 0, 1, 'C');
            $this->Ln(10);
        }

        // Pie de página
        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 10);

    // Encabezados de la tabla
    $pdf->Cell(40, 10, 'Proveedor', 1);
    $pdf->Cell(40, 10, 'Producto', 1);
    $pdf->Cell(30, 10, 'Fecha', 1);
    $pdf->Cell(20, 10, 'Cantidad', 1);
    $pdf->Cell(40, 10, 'Tipo Movimiento', 1);
    $pdf->Cell(50, 10, 'Observaciones', 1);
    $pdf->Ln();

    // Contenido de la tabla
    foreach ($resultados as $fila) {
        $pdf->Cell(40, 10, utf8_decode($fila['Proveedor']), 1);
        $pdf->Cell(40, 10, utf8_decode($fila['Producto']), 1);
        $pdf->Cell(30, 10, utf8_decode($fila['Fecha_Movimiento']), 1);
        $pdf->Cell(20, 10, utf8_decode($fila['Cantidad']), 1);
        $pdf->Cell(40, 10, utf8_decode($fila['Tipo_Movimiento']), 1);
        $pdf->Cell(50, 10, utf8_decode($fila['Observaciones']), 1);
        $pdf->Ln();
    }

    // Salida del PDF
    $pdf->Output('I', 'Reporte_Compras_Proveedor.pdf');
}

<?php
require_once('conexion.php');
require_once('../tcpdf/tcpdf.php');

header('Content-Type: application/pdf');

$datos = json_decode(file_get_contents('php://input'), true);

class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 15, 'Reporte de ' . ucfirst($this->tipoReporte), 0, false, 'C', 0);
    }
    
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0);
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->tipoReporte = $datos['tipoReporte'];

$pdf->SetCreator('Sistema de Inventario');
$pdf->SetAuthor('Administrador');
$pdf->SetTitle('Reporte de ' . ucfirst($datos['tipoReporte']));

$pdf->AddPage();

// Aquí va la lógica para obtener y mostrar los datos según el tipo de reporte
// ...

$pdf->Output('reporte.pdf', 'D'); 
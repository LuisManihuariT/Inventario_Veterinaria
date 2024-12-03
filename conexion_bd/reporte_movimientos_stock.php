<?php
require_once("conexion.php");

function obtenerMovimientosStock($fecha_inicio, $fecha_fin) {
    global $pdo;

    // Preparar la consulta para obtener los movimientos de stock entre las fechas
    $stmt = $pdo->prepare("SELECT p.Nombre AS Producto, u.Nombre AS Usuario, m.Tipo_Movimiento, m.Cantidad, m.Fecha_Movimiento, m.Observaciones
                            FROM movimiento_stock m
                            JOIN producto p ON m.ID_Producto = p.ID_Producto
                            JOIN usuario u ON m.ID_Usuario = u.ID_Usuario
                            WHERE m.Fecha_Movimiento BETWEEN :fecha_inicio AND :fecha_fin");
    $stmt->bindParam(':fecha_inicio', $fecha_inicio);
    $stmt->bindParam(':fecha_fin', $fecha_fin);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener los resultados
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function generarPDF($fecha_inicio, $fecha_fin, $resultados) {
    require_once('../fpdf/fpdf.php');

    $pdf = new FPDF();
    $pdf->AddPage();

    // Título
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Reporte de Movimientos de Stock', 0, 1, 'C');
    $pdf->Ln(10);

    // Subtítulo con las fechas
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Desde: ' . $fecha_inicio . ' hasta: ' . $fecha_fin, 0, 1, 'C');
    $pdf->Ln(10);

    // Encabezado de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Producto', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Usuario', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Tipo Movimiento', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Fecha Movimiento', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Observaciones', 1, 1, 'C');

    // Datos de la tabla
    $pdf->SetFont('Arial', '', 12);
    foreach ($resultados as $fila) {
        $pdf->Cell(40, 10, $fila['Producto'], 1);
        $pdf->Cell(40, 10, $fila['Usuario'], 1);
        $pdf->Cell(30, 10, $fila['Tipo_Movimiento'], 1);
        $pdf->Cell(30, 10, $fila['Cantidad'], 1);
        $pdf->Cell(40, 10, $fila['Fecha_Movimiento'], 1);
        $pdf->Cell(40, 10, $fila['Observaciones'], 1);
        $pdf->Ln();
    }

    // Salvar el archivo PDF
    $pdf->Output('D', 'reporte_movimientos_stock.pdf');
}
?>

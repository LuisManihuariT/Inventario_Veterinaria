<?php
require_once("conexion.php");
header('Content-Type: application/json');

$datos = json_decode(file_get_contents('php://input'), true);
$fechaInicio = $datos['fechaInicio'];
$fechaFin = $datos['fechaFin'];
$tipoReporte = $datos['tipoReporte'];

try {
    $resultado = [];
    $resumen = [];

    switch ($tipoReporte) {
        case 'movimientos':
            $sql = "SELECT DATE(Fecha_Movimiento) as fecha, COUNT(*) as valor 
                   FROM movimiento_stock 
                   WHERE Fecha_Movimiento BETWEEN ? AND ?
                   GROUP BY DATE(Fecha_Movimiento)";
            
            $sqlResumen = "SELECT 
                COUNT(*) as total_movimientos,
                SUM(CASE WHEN Tipo_Movimiento = 'Entrada' THEN 1 ELSE 0 END) as entradas,
                SUM(CASE WHEN Tipo_Movimiento = 'Salida' THEN 1 ELSE 0 END) as salidas
                FROM movimiento_stock 
                WHERE Fecha_Movimiento BETWEEN ? AND ?";
            break;

        case 'alertas':
            $sql = "SELECT DATE(Fecha_Alerta) as fecha, COUNT(*) as valor 
                   FROM alerta_stock 
                   WHERE Fecha_Alerta BETWEEN ? AND ?
                   GROUP BY DATE(Fecha_Alerta)";
            
            $sqlResumen = "SELECT 
                COUNT(*) as total_alertas,
                SUM(CASE WHEN Estado = 'Pendiente' THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN Estado = 'Atendida' THEN 1 ELSE 0 END) as atendidas
                FROM alerta_stock 
                WHERE Fecha_Alerta BETWEEN ? AND ?";
            break;
    }

    // Obtener datos para la gráfica
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$fechaInicio, $fechaFin]);
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener resumen
    $stmtResumen = $pdo->prepare($sqlResumen);
    $stmtResumen->execute([$fechaInicio, $fechaFin]);
    $resumen = $stmtResumen->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'datos' => $resultado,
        'resumen' => $resumen
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 
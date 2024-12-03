<?php
require_once("conexion.php");
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id_alerta'])) {
    try {
        $stmt = $pdo->prepare("
            UPDATE alerta_stock 
            SET Estado = 'Atendida', 
                Fecha_Atencion = NOW() 
            WHERE ID_Alerta = ?
        ");
        
        $resultado = $stmt->execute([$data['id_alerta']]);
        
        if ($resultado) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al actualizar']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ID de alerta no proporcionado']);
} 
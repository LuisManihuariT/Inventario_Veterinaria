<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $pdo->beginTransaction();

        $producto_id = $_POST['producto_id'];
        $nueva_cantidad = $_POST['cantidad'];
        $motivo = $_POST['motivo'];

        // Obtener stock actual
        $stmt = $pdo->prepare("SELECT Stock_Actual FROM producto WHERE ID_Producto = ?");
        $stmt->execute([$producto_id]);
        $producto = $stmt->fetch();
        
        $diferencia = $nueva_cantidad - $producto['Stock_Actual'];
        $tipo_movimiento = $diferencia > 0 ? 'Entrada' : 'Salida';
        
        // Registrar el movimiento
        $stmt = $pdo->prepare("
            INSERT INTO movimiento_stock (
                ID_Producto, 
                Fecha_Movimiento, 
                Tipo_Movimiento, 
                Cantidad, 
                Observaciones
            ) VALUES (?, NOW(), ?, ABS(?), ?)
        ");
        $stmt->execute([$producto_id, $tipo_movimiento, $diferencia, $motivo]);

        // Actualizar stock
        $stmt = $pdo->prepare("
            UPDATE producto 
            SET Stock_Actual = ? 
            WHERE ID_Producto = ?
        ");
        $stmt->execute([$nueva_cantidad, $producto_id]);

        $pdo->commit();
        header('Location: ../view/control.php?mensaje=Stock actualizado correctamente');
    } catch (PDOException $e) {
        $pdo->rollBack();
        header('Location: ../view/control.php?error=' . urlencode($e->getMessage()));
    }
    exit;
}
?> 
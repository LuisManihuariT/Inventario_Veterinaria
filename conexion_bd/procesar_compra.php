<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $pdo->beginTransaction();

        $producto_id = $_POST['producto_id'];
        $cantidad = $_POST['cantidad'];
        $precio_compra = $_POST['precio_compra'];

        // Registrar el movimiento de stock
        $query = $pdo->prepare("
            INSERT INTO movimiento_stock (
                ID_Producto, 
                Fecha_Movimiento, 
                Tipo_Movimiento, 
                Cantidad, 
                Observaciones
            ) VALUES (?, NOW(), 'Entrada', ?, 'Compra registrada')
        ");
        $query->execute([$producto_id, $cantidad]);

        // Actualizar el stock y precio de compra del producto
        $query = $pdo->prepare("
            UPDATE producto 
            SET Stock_Actual = Stock_Actual + ?,
                Precio_Compra = ?
            WHERE ID_Producto = ?
        ");
        $query->execute([$cantidad, $precio_compra, $producto_id]);

        $pdo->commit();

        // Verificar si el stock está por debajo del mínimo
        $query = $pdo->prepare("
            SELECT Stock_Actual, Stock_Minimo 
            FROM producto 
            WHERE ID_Producto = ?
        ");
        $query->execute([$producto_id]);
        $producto = $query->fetch();

        if ($producto['Stock_Actual'] <= $producto['Stock_Minimo']) {
            // Crear alerta de stock bajo
            $query = $pdo->prepare("
                INSERT INTO alerta_stock (
                    ID_Producto, 
                    Fecha_Alerta, 
                    Estado, 
                    Descripcion, 
                    Tipo_Alerta
                ) VALUES (?, NOW(), 'Pendiente', 'Stock bajo después de compra', 'Stock Bajo')
            ");
            $query->execute([$producto_id]);
        }

        header('Location: ../view/compra.php?mensaje=Compra registrada exitosamente&tipo=exito');
    } catch (PDOException $e) {
        $pdo->rollBack();
        header('Location: ../view/compra.php?mensaje=Error al registrar la compra: ' . $e->getMessage() . '&tipo=error');
    }
    exit;
}
?>

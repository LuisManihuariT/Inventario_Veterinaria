<?php
require_once("conexion.php");

function obtenerComprasPorProveedor($fecha_inicio, $fecha_fin) {
    global $pdo;

    $sql = "
        SELECT 
            pr.Nombre AS Proveedor,
            p.Nombre AS Producto,
            ms.Fecha_Movimiento,
            ms.Cantidad,
            ms.Tipo_Movimiento,
            ms.Observaciones
        FROM movimiento_stock ms
        INNER JOIN producto p ON ms.ID_Producto = p.ID_Producto
        INNER JOIN proveedor_producto pp ON pp.ID_Producto = p.ID_Producto
        INNER JOIN proveedor pr ON pr.ID_Proveedor = pp.ID_Proveedor
        WHERE ms.Tipo_Movimiento = 'Entrada'
        AND ms.Fecha_Movimiento BETWEEN :fecha_inicio AND :fecha_fin
        ORDER BY pr.Nombre, ms.Fecha_Movimiento ASC;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':fecha_inicio', $fecha_inicio);
    $stmt->bindParam(':fecha_fin', $fecha_fin);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
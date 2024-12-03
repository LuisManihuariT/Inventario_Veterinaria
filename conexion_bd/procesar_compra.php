<?php
require_once '../conexion_bd/conexion.php'; // Asegúrate de que la conexión esté correcta

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $precio_compra = $_POST['precio_compra'];

    // Insertar la compra en la base de datos
    $query = $pdo->prepare("INSERT INTO movimiento_stock (ID_Producto, Fecha_Movimiento, Tipo_Movimiento, Cantidad, Observaciones) VALUES (?, NOW(), 'Entrada', ?, 'Compra registrada')");
    $query->execute([$producto_id, $cantidad]);

    // Actualizar el stock del producto
    $query = $pdo->prepare("UPDATE producto SET Stock_Actual = Stock_Actual + ? WHERE ID_Producto = ?");
    $query->execute([$cantidad, $producto_id]);

    // Redireccionar o mostrar un mensaje de éxito
    header('Location: ../view/dashboard.php');
    exit;
}
?>

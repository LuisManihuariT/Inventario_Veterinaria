<?php
require_once("conexion.php"); 

if (isset($_GET['id'])) {
    $id_producto = htmlspecialchars($_GET['id']);

    // Preparar la consulta de eliminación
    $sql = "DELETE FROM producto WHERE ID_Producto = :id_producto";

    // Preparar la declaración
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);

    // Ejecutar la declaración
    if ($stmt->execute()) {
        echo "Producto eliminado exitosamente.";
    } else {
        echo "Error al eliminar el producto.";
    }
} else {
    echo "No se ha especificado el ID del producto.";
}

header("Location: ../view/inventario.php");
exit();
?>
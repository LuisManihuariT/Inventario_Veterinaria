<?php
require_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $Id_Producto = $_POST["Id_Producto"];
    $Nombre = $_POST["Nombre"];
    $Descripcion = $_POST["Descripcion"];
    $Categoria = $_POST["Categoria"];
    $Precio_Compra = $_POST["Precio_Compra"];
    $Precio_Venta = $_POST["Precio_Venta"];
    $Stock_Actual = $_POST["Stock_Actual"];
    $Stock_Minimo = $_POST["Stock_Minimo"];
    $Codigo_Producto = $_POST["Codigo_Producto"];
    $Fecha_vencimiento = $_POST["Fecha_vencimiento"];

    // Validación de campos
    if (empty($Id_Producto) || empty($Nombre) || empty($Precio_Compra) || empty($Precio_Venta) || empty($Stock_Actual) || empty($Stock_Minimo) || empty($Codigo_Producto)) {
        echo json_encode(["success" => false, "message" => "Todos los campos obligatorios deben ser completados."]);
        exit;
    }

    // Actualización en la base de datos
    $query = "UPDATE producto SET 
                Nombre = ?, 
                Descripcion = ?, 
                Categoria = ?, 
                Precio_Compra = ?, 
                Precio_Venta = ?, 
                Stock_Actual = ?, 
                Stock_Minimo = ?, 
                Codigo_Producto = ?, 
                Fecha_vencimiento = ? 
              WHERE Id_Producto = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param(
        "sssdidissi",
        $Nombre,
        $Descripcion,
        $Categoria,
        $Precio_Compra,
        $Precio_Venta,
        $Stock_Actual,
        $Stock_Minimo,
        $Codigo_Producto,
        $Fecha_vencimiento,
        $Id_Producto
    );

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Producto actualizado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar el producto."]);
    }

    $stmt->close();
    $conexion->close();
} elseif ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["Id_Producto"])) {
    $Id_Producto = $_GET["Id_Producto"];
    $query = "SELECT * FROM producto WHERE Id_Producto = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $Id_Producto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["success" => false, "message" => "Producto no encontrado."]);
    }

    $stmt->close();
    $conexion->close();
}
?>


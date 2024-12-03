<?php
require_once("../conexion_bd/conexion.php"); // Asegúrate de que el archivo de conexión esté correcto

// Obtener todos los productos para mostrarlos en el formulario
$stmt = $pdo->prepare("SELECT * FROM producto WHERE Stock_Actual > 0");
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Procesar el formulario de venta
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    // Verificar si hay suficiente stock
    $stmt = $pdo->prepare("SELECT Stock_Actual FROM producto WHERE ID_Producto = :id");
    $stmt->bindParam(':id', $producto_id);
    $stmt->execute();
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($producto['Stock_Actual'] >= $cantidad) {
        // Actualizar el stock del producto
        $nuevo_stock = $producto['Stock_Actual'] - $cantidad;

        $updateStmt = $pdo->prepare("UPDATE producto SET Stock_Actual = :nuevo_stock WHERE ID_Producto = :id");
        $updateStmt->bindParam(':nuevo_stock', $nuevo_stock);
        $updateStmt->bindParam(':id', $producto_id);
        $updateStmt->execute();

        // Insertar en la tabla de movimiento_stock
        $insertStmt = $pdo->prepare("INSERT INTO movimiento_stock (ID_Producto, Cantidad, Tipo_Movimiento, Fecha_Movimiento) VALUES (:id, :cantidad, 'Venta', NOW())");
        $insertStmt->bindParam(':id', $producto_id);
        $insertStmt->bindParam(':cantidad', $cantidad);
        $insertStmt->execute();

        echo "Venta registrada con éxito.";
    } else {
        echo "Stock insuficiente para completar la venta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Venta</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../resource/css/styles.css"> <!-- Tu CSS personalizado -->
</head>
<body class="fondo-background"> <!-- Agregar clase para el fondo -->
    <div class="container"> <!-- Estilo del contenedor ya definido en styles.css -->
        <h2 class="text-center">Registrar Venta</h2> <!-- Centrar el título -->

        <form method="POST">
            <div class="mb-3">
                <label for="producto_id" class="form-label">Producto</label>
                <select class="form-select" id="producto_id" name="producto_id" required>
                    <option value="">Seleccione un producto</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?php echo $producto['ID_Producto']; ?>">
                            <?php echo htmlspecialchars($producto['Nombre']) . " - Stock: " . $producto['Stock_Actual']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Venta</button>
        </form>
        <button onclick="window.history.back();" class="btn btn-secondary mt-3">Volver</button> <!-- Margen en la parte superior -->

    </div>
    
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

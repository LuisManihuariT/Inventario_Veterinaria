<?php
require_once '../conexion_bd/conexion.php'; // Asegúrate de que la conexión esté correcta

// Obtener productos de la base de datos
$query = $pdo->prepare("SELECT * FROM producto WHERE Stock_Actual > 0"); // Puedes ajustar la consulta según tus necesidades
$query->execute();
$productos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Compra</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../resource/css/styles.css"> <!-- Tu CSS personalizado -->
</head>
<body class="fondo-background"> <!-- Agregar clase para el fondo -->
    <div class="container"> <!-- Estilo del contenedor ya definido en styles.css -->
        <h2 class="text-center">Registrar Compra</h2> <!-- Centrar el título -->

        <form method="POST" action="../conexion_bd/procesar_compra.php"> <!-- Asegúrate de crear el archivo procesar_compra.php -->
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
            <div class="mb-3">
                <label for="precio_compra" class="form-label">Precio de Compra</label>
                <input type="text" class="form-control" id="precio_compra" name="precio_compra" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Compra</button>
        </form>

        <!-- Botón para volver a la página anterior -->
        <button onclick="window.history.back();" class="btn btn-secondary mt-3">Volver</button>
    </div>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require_once("../conexion_bd/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Obtener datos del formulario
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $categoria = $_POST['categoria'];
        $precio_compra = $_POST['precio_compra'];
        $precio_venta = $_POST['precio_venta'];
        $stock_actual = $_POST['stock_actual'];
        $stock_minimo = $_POST['stock_minimo'];
        $proveedor = $_POST['proveedor'];
        
        // Generar código de producto automático (puedes modificar esta lógica)
        $codigo_producto = 'PROD_' . time();

        // Insertar en la base de datos
        $sql = "INSERT INTO producto (Nombre, Descripcion, Categoria, Precio_Compra, 
                Precio_Venta, Stock_Actual, Stock_Minimo, Codigo_Producto) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nombre, $descripcion, $categoria, $precio_compra, 
            $precio_venta, $stock_actual, $stock_minimo, $codigo_producto
        ]);

        // Si se especificó un proveedor, crear la relación en proveedor_producto
        if (!empty($proveedor)) {
            $sql_proveedor = "INSERT INTO proveedor_producto (ID_Proveedor, ID_Producto) 
                             VALUES (?, LAST_INSERT_ID())";
            $stmt = $pdo->prepare($sql_proveedor);
            $stmt->execute([$proveedor]);
        }

        header("Location: inventario.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error al agregar el producto: " . $e->getMessage();
    }
}

// Obtener lista de proveedores para el select
$stmt = $pdo->query("SELECT ID_Proveedor, Nombre FROM proveedor");
$proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../resource/css/agregarproducto.css">
</head>
<body>
    <div class="modal">
        <div class="modal-content">
            <a href="inventario.php" class="close-button">&times;</a>
            <h2>Agregar Producto</h2>
            <hr class="modal-divider">
            
            <?php if (isset($error)): ?>
                <div class="mensaje-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="linea">
                    <div class="espacio-caja">
                        <label for="producto-id">ID:</label>
                        <input type="text" id="producto-id" class="input-field" disabled>
                    </div>
                    <div class="espacio-caja">
                        <label for="categoria">Categoría:</label>
                        <select id="categoria" name="categoria" class="input-field" required>
                            <option value="" disabled selected>Seleccionar categoría</option>
                            <option value="Alimentos">Alimentos</option>
                            <option value="Accesorios">Accesorios y equipamiento</option>
                            <option value="Transportes">Transportes y dormitorios</option>
                            <option value="Higiene">Higiene y Limpieza</option>
                        </select>
                    </div>
                </div>

                <div class="espacio-caja">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="input-field" required>
                </div>

                <div class="linea">
                    <div class="espacio-caja">
                        <label for="precio_compra">P. de compra:</label>
                        <input type="number" step="0.01" id="precio_compra" name="precio_compra" class="input-field" required>
                    </div>
                    <div class="espacio-caja">
                        <label for="precio_venta">P. de venta:</label>
                        <input type="number" step="0.01" id="precio_venta" name="precio_venta" class="input-field" required>
                    </div>
                </div>

                <div class="linea">
                    <div class="espacio-caja">
                        <label for="stock_actual">Cantidad:</label>
                        <input type="number" id="stock_actual" name="stock_actual" class="input-field" required>
                    </div>
                    <div class="espacio-caja">
                        <label for="stock_minimo">Stock min.:</label>
                        <input type="number" id="stock_minimo" name="stock_minimo" class="input-field" required>
                    </div>
                </div>

                <div class="espacio-caja">
                    <label for="proveedor">Proveedor:</label>
                    <select id="proveedor" name="proveedor" class="input-field">
                        <option value="">Seleccionar proveedor</option>
                        <?php foreach ($proveedores as $proveedor): ?>
                            <option value="<?php echo $proveedor['ID_Proveedor']; ?>">
                                <?php echo htmlspecialchars($proveedor['Nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="espacio-caja">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" class="textarea-field"></textarea>
                </div>

                <div class="button-group">
                    <button type="submit" class="modal-button agregar-button">Agregar Producto</button>
                    <a href="inventario.php" class="modal-button cancelar-button">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

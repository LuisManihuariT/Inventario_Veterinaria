<?php
require_once '../conexion_bd/conexion.php';

// Obtener productos
$query = $pdo->prepare("
    SELECT p.*, GROUP_CONCAT(pr.Nombre) as Proveedores 
    FROM producto p 
    LEFT JOIN proveedor_producto pp ON p.ID_Producto = pp.ID_Producto 
    LEFT JOIN proveedor pr ON pp.ID_Proveedor = pr.ID_Proveedor 
    GROUP BY p.ID_Producto
");
$query->execute();
$productos = $query->fetchAll(PDO::FETCH_ASSOC);

// Verificar si hay mensaje de éxito o error
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Compra</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../resource/css/pedidos.css">
    <style>
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #EFC02C;
            margin: 20px auto;
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: 'Quicksand', sans-serif;
        }

        .mensaje {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }

        .mensaje-exito {
            background-color: #d4edda;
            color: #155724;
        }

        .mensaje-error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .producto-info {
            display: none;
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registrar Compra</h1>

        <?php if ($mensaje): ?>
            <div class="mensaje mensaje-<?php echo $tipo; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form id="compraForm" method="POST" action="../conexion_bd/procesar_compra.php">
                <div class="form-group">
                    <label for="producto_id">Producto</label>
                    <select id="producto_id" name="producto_id" required>
                        <option value="">Seleccione un producto</option>
                        <?php foreach ($productos as $producto): ?>
                            <option value="<?php echo $producto['ID_Producto']; ?>" 
                                    data-precio="<?php echo $producto['Precio_Compra']; ?>"
                                    data-stock="<?php echo $producto['Stock_Actual']; ?>">
                                <?php echo htmlspecialchars($producto['Nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="productoInfo" class="producto-info">
                    <p>Stock actual: <span id="stockActual">0</span></p>
                    <p>Precio de compra anterior: S/.<span id="precioAnterior">0.00</span></p>
                    <p>Proveedor(es): <span id="proveedores">-</span></p>
                </div>

                <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" id="cantidad" name="cantidad" min="1" required>
                </div>

                <div class="form-group">
                    <label for="precio_compra">Precio de Compra Unitario (S/.)</label>
                    <input type="number" id="precio_compra" name="precio_compra" step="0.01" required>
                </div>

                <div class="form-group">
                    <label>Total: S/.<span id="total">0.00</span></label>
                </div>

                <div class="buttons">
                    <button type="submit" class="add-product">Registrar Compra</button>
                    <button type="button" class="return" onclick="window.location.href='inventario.php'">Regresar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('producto_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const productoInfo = document.getElementById('productoInfo');
            
            if (this.value) {
                document.getElementById('stockActual').textContent = selectedOption.dataset.stock;
                document.getElementById('precioAnterior').textContent = selectedOption.dataset.precio;
                document.getElementById('precio_compra').value = selectedOption.dataset.precio;
                productoInfo.style.display = 'block';
            } else {
                productoInfo.style.display = 'none';
            }
        });

        // Calcular total
        function calcularTotal() {
            const cantidad = document.getElementById('cantidad').value;
            const precio = document.getElementById('precio_compra').value;
            const total = (cantidad * precio).toFixed(2);
            document.getElementById('total').textContent = total;
        }

        document.getElementById('cantidad').addEventListener('input', calcularTotal);
        document.getElementById('precio_compra').addEventListener('input', calcularTotal);
    </script>
</body>
</html>

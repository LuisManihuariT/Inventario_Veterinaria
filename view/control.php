<?php
require_once("../conexion_bd/conexion.php");

// Obtener productos con su información de stock
$query = "SELECT 
    p.ID_Producto,
    p.Nombre,
    p.Stock_Actual,
    p.Stock_Minimo,
    p.Categoria,
    MAX(m.Fecha_Movimiento) as ultimo_movimiento
    FROM producto p
    LEFT JOIN movimiento_stock m ON p.ID_Producto = m.ID_Producto
    GROUP BY p.ID_Producto";

$stmt = $pdo->query($query);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener categorías únicas
$stmt = $pdo->query("SELECT DISTINCT Categoria FROM producto WHERE Categoria IS NOT NULL");
$categorias = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Stock</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../resource/css/control.css">
</head>
<body>
    <div class="container">
        <h1>Control de Stock</h1>
        <div class="filter-bar">
            <div class="search-group">
                <input type="text" id="buscarProducto" placeholder="Buscar producto">
                <button id="buscarProductoBtn">Buscar</button>
            </div>
            <div class="filter-group">
                <label for="categoria">Categoría:</label>
                <select id="categoria">
                    <option value="">Todas</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo htmlspecialchars($categoria); ?>">
                            <?php echo htmlspecialchars($categoria); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="inventory-table">
            <div class="column column-id">
                <div class="header">ID</div>
                <?php foreach ($productos as $producto): ?>
                    <div class="cell"><?php echo $producto['ID_Producto']; ?></div>
                <?php endforeach; ?>
            </div>
            <div class="column column-producto">
                <div class="header">Producto</div>
                <?php foreach ($productos as $producto): ?>
                    <div class="cell"><?php echo htmlspecialchars($producto['Nombre']); ?></div>
                <?php endforeach; ?>
            </div>
            <div class="column column-stock">
                <div class="header">Stock Actual</div>
                <?php foreach ($productos as $producto): ?>
                    <div class="cell"><?php echo $producto['Stock_Actual']; ?></div>
                <?php endforeach; ?>
            </div>
            <div class="column column-minimo">
                <div class="header">Stock Mínimo</div>
                <?php foreach ($productos as $producto): ?>
                    <div class="cell"><?php echo $producto['Stock_Minimo']; ?></div>
                <?php endforeach; ?>
            </div>
            <div class="column column-movimiento">
                <div class="header">Movimiento</div>
                <?php foreach ($productos as $producto): ?>
                    <div class="cell">
                        <button class="btn-ajustar" 
                                onclick="abrirModalAjuste(<?php echo htmlspecialchars(json_encode($producto)); ?>)">
                            Ajustar
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Modal para ajuste de stock -->
    <div id="modalAjuste" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Ajustar Stock</h3>
            <form id="formAjuste" action="../conexion_bd/ajustar_stock.php" method="POST">
                <input type="hidden" id="producto_id" name="producto_id">
                <div class="form-group">
                    <label>Producto: <span id="nombreProducto"></span></label>
                </div>
                <div class="form-group">
                    <label>Stock Actual: <span id="stockActual"></span></label>
                </div>
                <div class="form-group">
                    <label for="cantidad">Nueva Cantidad:</label>
                    <input type="number" id="cantidad" name="cantidad" required>
                </div>
                <div class="form-group">
                    <label for="motivo">Motivo del Ajuste:</label>
                    <textarea id="motivo" name="motivo" required></textarea>
                </div>
                <div class="button-group">
                    <button type="submit" class="btn-guardar">Guardar</button>
                    <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="buttons">
        <button class="add-product" onclick="window.location.href='agregar_producto.php'">Añadir Producto</button>
        <button class="return" onclick="window.location.href='inventario.php'">Regresar</button>
    </div>

    <script>
        // Funcionalidad de búsqueda
        document.getElementById('buscarProductoBtn').addEventListener('click', buscarProductos);
        document.getElementById('buscarProducto').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') buscarProductos();
        });

        document.getElementById('categoria').addEventListener('change', buscarProductos);

        function buscarProductos() {
            const busqueda = document.getElementById('buscarProducto').value.toLowerCase();
            const categoria = document.getElementById('categoria').value.toLowerCase();
            const filas = document.querySelectorAll('.inventory-table .cell');
            
            let index = 0;
            while (index < filas.length) {
                const nombre = filas[index + 1].textContent.toLowerCase();
                const categoriaProducto = filas[index].dataset.categoria?.toLowerCase();
                const mostrar = (nombre.includes(busqueda) || !busqueda) && 
                              (!categoria || categoriaProducto === categoria);
                
                for (let i = 0; i < 5; i++) {
                    filas[index + i].style.display = mostrar ? '' : 'none';
                }
                index += 5;
            }
        }

        // Funcionalidad del modal
        function abrirModalAjuste(producto) {
            document.getElementById('modalAjuste').style.display = 'flex';
            document.getElementById('producto_id').value = producto.ID_Producto;
            document.getElementById('nombreProducto').textContent = producto.Nombre;
            document.getElementById('stockActual').textContent = producto.Stock_Actual;
            document.getElementById('cantidad').value = producto.Stock_Actual;
        }

        function cerrarModal() {
            document.getElementById('modalAjuste').style.display = 'none';
        }
    </script>
</body>
</html> 
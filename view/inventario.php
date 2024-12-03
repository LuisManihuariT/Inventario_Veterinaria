<?php require_once("../conexion_bd/select_productos.php");?>
<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.html'); // Redirigir al login si no está autenticado
    exit();
}

// Obtener el nombre del usuario desde la sesión
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../resource/css/productos.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="user-info">
                <div class="avatar">
                    <img src="../img/admin.svg" alt="Avatar del Administrador">
                </div>
                <div class="user-details">
                    <h3>Administrador</h3>
                    <p>administrador@gmail.com</p>
                </div>
                <div class="icono-barra">
                    <img src="../img/barra.svg" alt="Botón para contraer barra" id="icono-sidebar">
                </div>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="inventario.php" data-text="Productos"><img src="../img/producto.svg" alt="Icono de productos"> <span>Productos</span></a></li>
                    <li><a href="reporte_compras_proveedor.php" data-text="Proveedores"><img src="../img/proveedores.svg" alt="Icono de proveedores"> <span>Proveedores</span></a></li>
                    <li><a href="compra.php" data-text="Pedidos"><img src="../img/pedidos.svg" alt="Icono de pedidos"> <span>Pedidos</span></a></li>
                    <li><a href="control.php" data-text="Control de stock"><img src="../img/stock.svg" alt="Icono de stock"> <span>Control de stock</span></a></li>
                    <li><a href="reportes.html" data-text="Reportes"><img src="../img/reportes.svg" alt="Icono de reportes"> <span>Reportes</span></a></li>
                    <li><a href="notificacion.php" data-text="Notificaciones"><img src="../img/notificaciones.svg" alt="Icono de notificaciones"> <span>Notificaciones</span></a></li>
                    <li><a href="#" data-text="Cuentas"><img src="../img/cuentas.svg" alt="Icono de cuentas"> <span>Cuentas</span></a></li>
                </ul>
            </div>        
            <div class="logout">
                <ul><li><a href="../conexion_bd/cerrar_sesion.php" data-text="Salir"><img src="../img/salir.svg" alt="Icono de salir"><span>Salir</span></a></li></ul>
            </div>
        </div>
        <div class="derecha">
            <h1>Inventario de Productos</h1>
            <div class="filter-bar">
                <input type="text" id="buscarProducto" placeholder="Buscar producto">
                <button id="buscarBtn">Buscar</button>
                
                <div class="filter-group">
                    <label for="categoria">Categoría:</label>
                    <select id="categoria">
                        <option value="" disabled selected>Categoría</option>
                        <option value="Alimentos">Alimentos</option>
                        <option value="Accesorios">Accesorios y equipamiento</option>
                        <option value="Transportes">Transportes y dormitorios</option>
                        <option value="Higiene">Higiene y Limpieza</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="stock">Stock:</label>
                    <div class="stock-dropdown">
                        <select id="stock" class="stock-select">
                            <option value="" disabled selected>No seleccionado</option>
                            <option value="range">Especificar rango</option>
                        </select>
                        <div id="stockDropdown" class="dropdown-content">
                            <div class="range-input">
                                <label for="stockLow">Stock más bajo:</label>
                                <input type="number" id="stockLow" placeholder="Ej: 10">
                            </div>
                            <div class="range-input">
                                <label for="stockHigh">Stock más alto:</label>
                                <input type="number" id="stockHigh" placeholder="Ej: 50">
                            </div>
                            <button id="applyStockFilter">Mostrar</button>
                        </div>
                    </div>
                </div>
                
                <div class="filter-group">
                    <label for="orden">Ordenar por:</label>
                    <select id="orden">
                        <option value="" disabled selected>Ordenar por</option>
                        <option value="cantidadDesc">Cant. actual: De mayor a menor</option>
                        <option value="cantidadAsc">Cant. actual: De menor a mayor</option>
                        <option value="proveedorAZ">Proveedor: De A a Z</option>
                        <option value="proveedorZA">Proveedor: De Z a A</option>
                        <option value="precioDesc">Precio: De mayor a menor</option>
                        <option value="precioAsc">Precio: De menor a mayor</option>
                    </select>
                </div>
            </div>

                
                <table class="table">
                    <thead>
                        <tr >
                            <th class = "column column-producto header">ID</th>
                            <th class = "column column-producto header">Nombre</th>
                            <th class = "column column-producto header">Descripción</th>
                            <th class = "column column-producto header">Categoría</th>
                            <th class = "column column-producto header">Precio de Compra</th>
                            <th class = "column column-producto header">Precio de Venta</th>
                            <th class = "column column-producto header">Stock Actual</th>
                            <th class = "column column-producto header">Stock Mínimo</th>
                            <th class = "column column-producto header">Código</th>
                            <th class = "column column-producto header">Fecha de Vencimiento</th>
                            <th class = "column column-producto header">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php if (count($productos) > 0): ?>
                            <?php foreach ($productos as $producto): ?>
                                <tr class = "column column-producto">
                                    <td><?php echo htmlspecialchars($producto['ID_Producto']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['Nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['Descripcion']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['Categoria']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['Precio_Compra']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['Precio_Venta']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['Stock_Actual']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['Stock_Minimo']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['Codigo_Producto']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['Fecha_Vencimiento']); ?></td>
                                    <td>
                                        <a href="editar.php?id=<?php echo $producto['ID_Producto']; ?>" class="edit">Editar</a>
                                        <a href="../conexion_bd/eliminar.php?id=<?php echo $producto['ID_Producto']; ?>"><img src="../img/boton-eliminar.png" class="delete-image"></a>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center">No hay productos en el inventario.</td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                    <div class="buttons">
                    <a href="agregar_producto.php" class="add-product">Agregar Nuevo Producto</a>
                    <a href="../conexion_bd/generar_reporte.php" class="add-product">Generar Reporte PDF</a>
                    <button class="return">Regresar</button>
                </div>
                </table>
            </div>
        </div>
    </div> 
    <script src="../Models/barralat.js"></script>                  
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

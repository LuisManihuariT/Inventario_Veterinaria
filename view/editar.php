<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="../resource/css/editarproducto.css">
    <script src="../models/editar.js" defer></script>
</head>
<body>
    <div class="modal">
        <div class="modal-content">
            <button class="close-button" onclick="window.location.href='inventario.php'">×</button>
            <h2>Editar Producto</h2>
            <form id="editarProductoForm">
                <input type="hidden" id="Id_Producto" name="Id_Producto">

                <div class="linea">
                    <div class="espacio-caja">
                        <label for="Nombre">Nombre del Producto:</label>
                        <input type="text" id="Nombre" name="Nombre" required class="input-field">
                    </div>
                    <div class="espacio-caja">
                        <label for="Codigo_Producto">Código del Producto:</label>
                        <input type="text" id="Codigo_Producto" name="Codigo_Producto" required class="input-field">
                    </div>
                </div>

                <div class="espacio-caja">
                    <label for="Descripcion">Descripción:</label>
                    <textarea id="Descripcion" name="Descripcion" class="textarea-field" rows="4"></textarea>
                </div>

                <div class="linea">
                    <div class="espacio-caja">
                        <label for="Categoria">Categoría:</label>
                        <input type="text" id="Categoria" name="Categoria" class="input-field">
                    </div>
                    <div class="espacio-caja">
                        <label for="Precio_Compra">Precio de Compra:</label>
                        <input type="number" id="Precio_Compra" name="Precio_Compra" step="0.01" required class="input-field">
                    </div>
                </div>

                <div class="linea">
                    <div class="espacio-caja">
                        <label for="Precio_Venta">Precio de Venta:</label>
                        <input type="number" id="Precio_Venta" name="Precio_Venta" step="0.01" required class="input-field">
                    </div>
                    <div class="espacio-caja">
                        <label for="Stock_Actual">Stock Actual:</label>
                        <input type="number" id="Stock_Actual" name="Stock_Actual" required class="input-field">
                    </div>
                </div>

                <div class="linea">
                    <div class="espacio-caja">
                        <label for="Stock_Minimo">Stock Mínimo:</label>
                        <input type="number" id="Stock_Minimo" name="Stock_Minimo" required class="input-field">
                    </div>
                    <div class="espacio-caja">
                        <label for="Fecha_vencimiento">Fecha de Vencimiento:</label>
                        <input type="date" id="Fecha_vencimiento" name="Fecha_vencimiento" class="input-field">
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="modal-button agregar-button">Guardar Cambios</button>
                    <button type="button" class="modal-button cancelar-button" onclick="window.location.href='inventario.php'">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

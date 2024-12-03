document.addEventListener('DOMContentLoaded', function() {
    cargarProductos();
    
    // Event listeners para los filtros
    document.getElementById('buscarBtn').addEventListener('click', aplicarFiltros);
    document.getElementById('categoria').addEventListener('change', aplicarFiltros);
    document.getElementById('orden').addEventListener('change', aplicarFiltros);
    document.getElementById('applyStockFilter').addEventListener('click', aplicarFiltros);
});

function cargarProductos() {
    fetch('../conexion_bd/obtener_productos.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarProductos(data.data);
            } else {
                console.error('Error al cargar productos:', data.error);
            }
        })
        .catch(error => console.error('Error:', error));
}

function mostrarProductos(productos) {
    const tbody = document.querySelector('tbody');
    tbody.innerHTML = '';
    
    if (productos.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="11" class="text-center">No hay productos en el inventario.</td>
            </tr>`;
        return;
    }

    productos.forEach(producto => {
        const tr = document.createElement('tr');
        tr.className = 'column column-producto';
        tr.innerHTML = `
            <td>${escapeHtml(producto.ID_Producto)}</td>
            <td>${escapeHtml(producto.Nombre)}</td>
            <td>${escapeHtml(producto.Descripcion)}</td>
            <td>${escapeHtml(producto.Categoria)}</td>
            <td>${escapeHtml(producto.Precio_Compra)}</td>
            <td>${escapeHtml(producto.Precio_Venta)}</td>
            <td>${escapeHtml(producto.Stock_Actual)}</td>
            <td>${escapeHtml(producto.Stock_Minimo)}</td>
            <td>${escapeHtml(producto.Codigo_Producto)}</td>
            <td>${escapeHtml(producto.Fecha_Vencimiento)}</td>
            <td>
                <a href="editar.php?id=${producto.ID_Producto}" class="edit">Editar</a>
                <a href="javascript:void(0)" onclick="mostrarAdvertencia(${producto.ID_Producto})">
                    <img src="../img/boton-eliminar.png" class="delete-image">
                </a>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function aplicarFiltros() {
    // Implementar la lógica de filtrado aquí
    // Por ahora solo recarga los productos
    cargarProductos();
}
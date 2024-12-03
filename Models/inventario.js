document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad de la barra lateral
    inicializarBarraLateral();
    
    // Funcionalidad del inventario
    cargarProductos();
    inicializarFiltros();
});

function inicializarBarraLateral() {
    const sidebar = document.querySelector(".sidebar");
    const toggleButton = document.querySelector("#icono-sidebar");
    const menuLinks = document.querySelectorAll(".menu ul li a span");
    const logoutLink = document.querySelector(".logout ul li a span");
    const userDetails = document.querySelector(".user-details");

    let isMinimized = false;

    if (!sidebar || !toggleButton) {
        console.error("Elementos esenciales no encontrados. Revisa los selectores.");
        return;
    }

    toggleButton.addEventListener("click", () => {
        isMinimized = !isMinimized;

        if (isMinimized) {
            sidebar.style.width = "70px";
            userDetails.style.display = "none";
            menuLinks.forEach(link => link.style.display = "none");
            if (logoutLink) logoutLink.style.display = "none";
            document.querySelector('.derecha').style.marginLeft = "70px";
        } else {
            sidebar.style.width = "250px";
            userDetails.style.display = "block";
            menuLinks.forEach(link => link.style.display = "inline");
            if (logoutLink) logoutLink.style.display = "inline";
            document.querySelector('.derecha').style.marginLeft = "250px";
        }
    });
}

function inicializarFiltros() {
    document.getElementById('buscarBtn').addEventListener('click', aplicarFiltros);
    document.getElementById('categoria').addEventListener('change', aplicarFiltros);
    document.getElementById('orden').addEventListener('change', aplicarFiltros);
    document.getElementById('applyStockFilter').addEventListener('click', aplicarFiltros);
    
    // Inicializar dropdown de stock
    const stockSelect = document.getElementById('stock');
    const stockDropdown = document.getElementById('stockDropdown');
    
    stockSelect.addEventListener('change', function() {
        if (this.value === 'range') {
            stockDropdown.style.display = 'block';
        } else {
            stockDropdown.style.display = 'none';
        }
    });
}

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
                <a href="../conexion_bd/eliminar.php?id=${producto.ID_Producto}">
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
    const busqueda = document.getElementById('buscarProducto').value;
    const categoria = document.getElementById('categoria').value;
    const orden = document.getElementById('orden').value;
    const stockLow = document.getElementById('stockLow').value;
    const stockHigh = document.getElementById('stockHigh').value;

    // Construir URL con parámetros de filtro
    let url = '../conexion_bd/obtener_productos.php?';
    if (busqueda) url += `busqueda=${encodeURIComponent(busqueda)}&`;
    if (categoria) url += `categoria=${encodeURIComponent(categoria)}&`;
    if (orden) url += `orden=${encodeURIComponent(orden)}&`;
    if (stockLow) url += `stockLow=${encodeURIComponent(stockLow)}&`;
    if (stockHigh) url += `stockHigh=${encodeURIComponent(stockHigh)}&`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarProductos(data.data);
            } else {
                console.error('Error al filtrar productos:', data.error);
            }
        })
        .catch(error => console.error('Error:', error));
}
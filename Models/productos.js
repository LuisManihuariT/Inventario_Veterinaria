document.addEventListener("DOMContentLoaded", () => {
    const buscarInput = document.getElementById("buscarProducto");
    const buscarBtn = document.getElementById("buscarBtn");
    const categoriaSelect = document.getElementById("categoria");
    const stockDropdownBtn = document.getElementById("stockDropdownBtn");
    const stockDropdown = document.getElementById("stockDropdown");
    const stockLowInput = document.getElementById("stockLow");
    const stockHighInput = document.getElementById("stockHigh");
    const applyStockFilter = document.getElementById("applyStockFilter");
    const ordenarSelect = document.getElementById("orden");

    const productos = [
        { id: 1, producto: "Rascador", categoria: "Accesorios", cantidad: 15, precio: 10, proveedor: "Proveedor A" },
        { id: 2, producto: "Bola de estambre", categoria: "Juguetes", cantidad: 30, precio: 5, proveedor: "Proveedor B" },
        { id: 3, producto: "Hierba gatuna", categoria: "Hierbas", cantidad: 20, precio: 8, proveedor: "Proveedor C" }
    ];

    let stockRange = { min: 0, max: Infinity };

    const renderTable = (filteredData) => {
        const tableBody = document.querySelector(".inventory-table");
        tableBody.innerHTML = "";

        filteredData.forEach(producto => {
            const row = `
                <div class="column column-id">${producto.id}</div>
                <div class="column column-producto">${producto.producto}</div>
                <div class="column column-categoria">${producto.categoria}</div>
                <div class="column column-cant">${producto.cantidad}</div>
                <div class="column column-precio">$${producto.precio}</div>
                <div class="column column-proveedor">${producto.proveedor}</div>
                <div class="column column-detalles">
                    <button class="details-button">+</button>
                </div>
            `;
            tableBody.innerHTML += row;
        });
    };

    const filterProducts = () => {
        let filteredData = [...productos];

        const searchQuery = buscarInput.value.toLowerCase();
        if (searchQuery) {
            filteredData = filteredData.filter(producto =>
                producto.producto.toLowerCase().includes(searchQuery)
            );
        }

        const selectedCategoria = categoriaSelect.value;
        if (selectedCategoria) {
            filteredData = filteredData.filter(producto => producto.categoria === selectedCategoria);
        }

        filteredData = filteredData.filter(producto =>
            producto.cantidad >= stockRange.min && producto.cantidad <= stockRange.max
        );

        const ordenarPor = ordenarSelect.value;
        if (ordenarPor === "cantidadDesc") {
            filteredData.sort((a, b) => b.cantidad - a.cantidad);
        } else if (ordenarPor === "cantidadAsc") {
            filteredData.sort((a, b) => a.cantidad - b.cantidad);
        } else if (ordenarPor === "proveedorAZ") {
            filteredData.sort((a, b) => a.proveedor.localeCompare(b.proveedor));
        } else if (ordenarPor === "proveedorZA") {
            filteredData.sort((a, b) => b.proveedor.localeCompare(a.proveedor));
        } else if (ordenarPor === "precioDesc") {
            filteredData.sort((a, b) => b.precio - a.precio);
        } else if (ordenarPor === "precioAsc") {
            filteredData.sort((a, b) => a.precio - b.precio);
        }

        renderTable(filteredData);
    };

    stockDropdownBtn.addEventListener("click", () => {
        stockDropdown.style.display =
            stockDropdown.style.display === "block" ? "none" : "block";
    });

    applyStockFilter.addEventListener("click", () => {
        const min = parseInt(stockLowInput.value, 10) || 0;
        const max = parseInt(stockHighInput.value, 10) || Infinity;

        stockRange = { min, max };
        stockDropdown.style.display = "none";

        if (min || max !== Infinity) {
            stockDropdownBtn.textContent = `${min} a ${max}`;
        } else {
            stockDropdownBtn.textContent = "No seleccionado";
        }

        filterProducts(); 
    });

    document.addEventListener("click", (event) => {
        if (!stockDropdown.contains(event.target) && event.target !== stockDropdownBtn) {
            stockDropdown.style.display = "none";
        }
    });

    buscarBtn.addEventListener("click", filterProducts);
    categoriaSelect.addEventListener("change", filterProducts);
    ordenarSelect.addEventListener("change", filterProducts);

    renderTable(productos);
});

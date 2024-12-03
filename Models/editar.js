document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("editarProductoForm");
    const urlParams = new URLSearchParams(window.location.search);
    const Id_Producto = urlParams.get("Id_Producto");

    if (Id_Producto) {
        fetch(`../conexion_bd/editar.php?Id_Producto=${Id_Producto}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.success !== false) {
                    Object.keys(data).forEach(key => {
                        const input = document.getElementById(key);
                        if (input) input.value = data[key];
                    });
                } else {
                    alert(data.message || "Error al cargar los datos del producto.");
                }
            })
            .catch(error => console.error("Error al cargar el producto:", error));
    }

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(form);

        fetch("../conexion_bd/editar.php", {
            method: "POST",
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = "inventario.php";
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error("Error al guardar los cambios:", error));
    });
});

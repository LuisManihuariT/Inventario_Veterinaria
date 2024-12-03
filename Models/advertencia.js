function mostrarAdvertencia(idProducto) {
    const messageContainer = document.querySelector('.message-container');
    messageContainer.style.display = 'block';

    // Manejar el botón de cerrar
    const closeButton = document.querySelector('.close-button');
    closeButton.addEventListener('click', () => {
        messageContainer.style.display = 'none';
    });

    // Manejar el botón de confirmar
    const confirmButton = document.querySelector('.confirm-button');
    confirmButton.addEventListener('click', () => {
        // Realizar la eliminación
        window.location.href = `../conexion_bd/eliminar.php?id=${idProducto}`;
    });
} 
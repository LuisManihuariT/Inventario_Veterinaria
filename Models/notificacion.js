document.addEventListener("DOMContentLoaded", () => {
    const filtrarNotificaciones = () => {
        const categoria = document.getElementById('categoriaProducto').value.toLowerCase();
        const fechaInicio = document.getElementById('fechaInicio').value;
        const fechaFin = document.getElementById('fechaFin').value;
        
        document.querySelectorAll('.tarjeta-notificacion').forEach(tarjeta => {
            const categoriaNotificacion = tarjeta.dataset.categoria.toLowerCase();
            const fechaNotificacion = tarjeta.dataset.fecha;
            
            let mostrar = true;
            
            if (categoria && categoriaNotificacion !== categoria) {
                mostrar = false;
            }
            
            if (fechaInicio && fechaNotificacion < fechaInicio) {
                mostrar = false;
            }
            
            if (fechaFin && fechaNotificacion > fechaFin) {
                mostrar = false;
            }
            
            tarjeta.style.display = mostrar ? '' : 'none';
        });
    };

    // Eventos para filtros
    document.getElementById('filtrarNotificaciones').addEventListener('click', filtrarNotificaciones);
    document.getElementById('categoriaProducto').addEventListener('change', filtrarNotificaciones);
    document.getElementById('fechaInicio').addEventListener('change', filtrarNotificaciones);
    document.getElementById('fechaFin').addEventListener('change', filtrarNotificaciones);
});

function atenderNotificacion(idAlerta) {
    fetch('../conexion_bd/atender_notificacion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id_alerta: idAlerta })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar la UI
            const boton = document.querySelector(`button[onclick="atenderNotificacion(${idAlerta})"]`);
            const contenedorBoton = boton.parentElement;
            boton.remove();
            contenedorBoton.innerHTML += '<span class="estado-atendido">Atendida</span>';
        } else {
            alert('Error al atender la notificación');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    });
}

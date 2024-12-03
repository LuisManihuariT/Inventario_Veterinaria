document.addEventListener("DOMContentLoaded", () => {
    inicializarFechas();
    inicializarEventos();
    actualizarTitulosResumen();
});

function inicializarFechas() {
    const fechaFin = document.getElementById('fecha-fin');
    const fechaInicio = document.getElementById('fecha-inicio');
    fechaFin.valueAsDate = new Date();
    fechaInicio.valueAsDate = new Date(Date.now() - 30 * 24 * 60 * 60 * 1000);
}

function inicializarEventos() {
    const botonGenerar = document.getElementById('boton-generar-reporte');
    const tipoReporte = document.getElementById('tipo-reporte');
    const botonesExportar = document.querySelectorAll('.boton-exportar');

    botonGenerar.addEventListener('click', generarReporte);
    tipoReporte.addEventListener('change', actualizarTitulosResumen);
    
    botonesExportar.forEach(boton => {
        boton.addEventListener('click', () => exportarReporte(boton.dataset.tipo));
    });
}

async function generarReporte() {
    mostrarCargando(true);
    const datos = obtenerParametrosReporte();

    try {
        const response = await fetch('../conexion_bd/obtener_datos_reporte.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });
        
        const resultado = await response.json();
        if (resultado.success) {
            actualizarInterfaz(resultado);
        } else {
            mostrarError('Error al obtener los datos: ' + resultado.error);
        }
    } catch (error) {
        mostrarError('Error de conexión con el servidor');
        console.error('Error:', error);
    } finally {
        mostrarCargando(false);
    }
}

function obtenerParametrosReporte() {
    return {
        fechaInicio: document.getElementById('fecha-inicio').value,
        fechaFin: document.getElementById('fecha-fin').value,
        tipoReporte: document.getElementById('tipo-reporte').value
    };
}

function actualizarInterfaz(datos) {
    actualizarGrafica(datos.datos);
    actualizarResumen(datos.resumen);
    actualizarTabla(datos.datos);
    habilitarBotonesExportar(true);
}

function actualizarGrafica(datos) {
    const ctx = document.getElementById('grafica-reporte').getContext('2d');
    const tipoReporte = document.getElementById('tipo-reporte').value;
    
    const config = {
        type: 'bar',
        data: {
            labels: datos.map(d => formatearFecha(d.fecha)),
            datasets: [{
                label: obtenerEtiquetaGrafica(tipoReporte),
                data: datos.map(d => d.valor),
                backgroundColor: '#EFC02C',
                borderColor: '#D1A322',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return tipoReporte === 'ventas' ? 'S/ ' + value : value;
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (tipoReporte === 'ventas') {
                                label += 'S/ ';
                            }
                            label += context.parsed.y;
                            return label;
                        }
                    }
                }
            }
        }
    };

    if (window.graficaActual) {
        window.graficaActual.destroy();
    }
    window.graficaActual = new Chart(ctx, config);
}

function actualizarTitulosResumen() {
    const tipo = document.getElementById('tipo-reporte').value;
    const titulos = {
        ventas: ['Total Ventas', 'Total Productos', 'Total Clientes'],
        movimientos: ['Total Movimientos', 'Entradas', 'Salidas'],
        alertas: ['Total Alertas', 'Pendientes', 'Atendidas']
    };

    const titulosActuales = titulos[tipo] || ['Total', 'Entrada', 'Salida'];
    document.querySelectorAll('.tarjeta-resumen h3').forEach((elemento, index) => {
        elemento.textContent = titulosActuales[index];
    });
}

function actualizarResumen(resumen) {
    const tipo = document.getElementById('tipo-reporte').value;
    
    document.getElementById('total-registros').textContent = 
        tipo === 'ventas' ? formatearMoneda(resumen.total) : resumen.total;
    
    document.getElementById('total-entradas').textContent = 
        tipo === 'ventas' ? resumen.productos : resumen.entradas;
    
    document.getElementById('total-salidas').textContent = 
        tipo === 'ventas' ? resumen.clientes : resumen.salidas;
}

function actualizarTabla(datos) {
    const tbody = document.getElementById('tabla-cuerpo');
    const tipo = document.getElementById('tipo-reporte').value;
    tbody.innerHTML = '';

    datos.forEach(dato => {
        const tr = document.createElement('tr');
        tr.innerHTML = generarFilaTabla(dato, tipo);
        tbody.appendChild(tr);
    });
}

function generarFilaTabla(dato, tipo) {
    const formatosFila = {
        ventas: `
            <td>${formatearFecha(dato.fecha)}</td>
            <td>${dato.producto}</td>
            <td>${formatearMoneda(dato.valor)}</td>
            <td>${dato.cliente}</td>
        `,
        movimientos: `
            <td>${formatearFecha(dato.fecha)}</td>
            <td>${dato.tipo_movimiento}</td>
            <td>${dato.cantidad}</td>
            <td>${dato.producto}</td>
        `,
        alertas: `
            <td>${formatearFecha(dato.fecha)}</td>
            <td>${dato.tipo_alerta}</td>
            <td>${dato.producto}</td>
            <td>${dato.estado}</td>
        `
    };

    return formatosFila[tipo] || '';
}

async function exportarReporte(tipo) {
    const datos = obtenerParametrosReporte();
    const url = tipo === 'pdf' ? 
        '../conexion_bd/exportar_reporte_pdf.php' : 
        '../conexion_bd/exportar_reporte_excel.php';

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });

        if (tipo === 'pdf') {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `reporte_${datos.tipoReporte}_${formatearFechaArchivo(new Date())}.pdf`;
            a.click();
        } else {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `reporte_${datos.tipoReporte}_${formatearFechaArchivo(new Date())}.xlsx`;
            a.click();
        }
    } catch (error) {
        mostrarError(`Error al exportar el reporte en formato ${tipo.toUpperCase()}`);
        console.error('Error:', error);
    }
}

// Funciones auxiliares
function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-PE', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function formatearFechaArchivo(fecha) {
    return fecha.toISOString().split('T')[0];
}

function formatearMoneda(valor) {
    return 'S/ ' + parseFloat(valor).toFixed(2);
}

function obtenerEtiquetaGrafica(tipo) {
    const etiquetas = {
        ventas: 'Ventas Diarias',
        movimientos: 'Movimientos de Stock',
        alertas: 'Alertas de Stock'
    };
    return etiquetas[tipo] || 'Datos';
}

function mostrarCargando(mostrar) {
    const boton = document.getElementById('boton-generar-reporte');
    const spinner = boton.querySelector('.indicador-carga');
    const texto = boton.querySelector('.texto-boton');
    
    spinner.hidden = !mostrar;
    texto.textContent = mostrar ? 'Generando...' : 'Generar Reporte';
    boton.disabled = mostrar;
}

function mostrarError(mensaje) {
    const contenedorError = document.getElementById('mensaje-error');
    contenedorError.textContent = mensaje;
    contenedorError.style.display = 'block';
    setTimeout(() => {
        contenedorError.style.display = 'none';
    }, 5000);
}

function habilitarBotonesExportar(habilitar) {
    document.querySelectorAll('.boton-exportar').forEach(boton => {
        boton.disabled = !habilitar;
    });
}

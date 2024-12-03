document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('form');
    const usuarioInput = document.getElementById('usuario');
    const contraseñaInput = document.getElementById('contraseña');
    
    // Agregar mensajes de error
    crearMensajeError(usuarioInput);
    crearMensajeError(contraseñaInput);

    // Validaciones en tiempo real
    usuarioInput.addEventListener('input', function() {
        validarUsuario(this);
    });

    contraseñaInput.addEventListener('input', function() {
        validarContraseña(this);
    });

    // Validación al enviar el formulario
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const usuarioValido = validarUsuario(usuarioInput);
        const contraseñaValida = validarContraseña(contraseñaInput);

        if (usuarioValido && contraseñaValida) {
            this.submit();
        }
    });
});

function crearMensajeError(input) {
    const mensajeError = document.createElement('span');
    mensajeError.className = 'mensaje-error';
    mensajeError.style.color = '#ff0000';
    mensajeError.style.fontSize = '12px';
    mensajeError.style.marginTop = '5px';
    mensajeError.style.display = 'none';
    input.parentNode.appendChild(mensajeError);
}

function mostrarError(input, mensaje) {
    const mensajeError = input.parentNode.querySelector('.mensaje-error');
    mensajeError.textContent = mensaje;
    mensajeError.style.display = 'block';
    input.classList.add('input-error');
}

function limpiarError(input) {
    const mensajeError = input.parentNode.querySelector('.mensaje-error');
    mensajeError.style.display = 'none';
    input.classList.remove('input-error');
}

function validarUsuario(input) {
    const valor = input.value.trim();
    
    // Limpiar espacios en blanco automáticamente
    if (valor !== input.value) {
        input.value = valor;
    }

    // Validaciones
    if (valor === '') {
        mostrarError(input, 'El usuario es requerido');
        return false;
    } else if (valor.length < 4) {
        mostrarError(input, 'El usuario debe tener al menos 4 caracteres');
        return false;
    } else if (valor.length > 20) {
        mostrarError(input, 'El usuario no puede tener más de 20 caracteres');
        return false;
    } else if (!/^[a-zA-Z0-9._-]+$/.test(valor)) {
        mostrarError(input, 'El usuario solo puede contener letras, números, puntos, guiones y guiones bajos');
        return false;
    }

    limpiarError(input);
    return true;
}

function validarContraseña(input) {
    const valor = input.value;

    // Validaciones
    if (valor === '') {
        mostrarError(input, 'La contraseña es requerida');
        return false;
    }

    limpiarError(input);
    return true;
} 
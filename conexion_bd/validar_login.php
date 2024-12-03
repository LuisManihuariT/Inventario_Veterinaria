<?php
session_start();
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    $sql = "SELECT * FROM usuario WHERE Nombre = :usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica si el usuario existe y si la contraseña es correcta
    if ($user && $user['Contraseña'] === $contraseña) {
        $_SESSION['usuario'] = $user['Nombre'];
        $_SESSION['rol'] = $user['Rol'];
        // Suponiendo que la validación del usuario es exitosa
        session_start();
        $_SESSION['usuario'] = $usuario; // Guardar el nombre de usuario en la sesión
        header('Location: ../view/inventario.php'); // Redirigir al dashboard
        exit();
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>


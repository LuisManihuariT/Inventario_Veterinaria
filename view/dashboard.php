<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.html'); // Redirigir al login si no está autenticado
    exit();
}

// Obtener el nombre del usuario desde la sesión
$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../resource/css/styles.css"> 
</head>
<body class="fondo-background"> <!-- Agregar clase para el fondo -->
    <div class="container mt-5 p-4 shadow bg-light rounded"> <!-- Estilos de margen, padding y sombra -->
        <h1 class="text-center mb-4">Bienvenido al Dashboard, <?php echo htmlspecialchars($usuario); ?>!</h1> <!-- Centrar el título -->

        <p class="text-center mb-4">Aquí podrás gestionar tus actividades.</p> <!-- Centrar el párrafo -->

        <div class="d-flex justify-content-center gap-2"> <!-- Usar d-flex para el espaciado entre botones -->
            <a href="inventario.php" class="btn btn-primary">Ver Inventario</a>
            <a href="venta.php" class="btn btn-primary">Registrar Venta</a>
            <a href="compra.php" class="btn btn-primary">Registrar Compra</a>
            <a href="reporte_compras_proveedor.php" class="btn btn-primary">Reporte proveedor</a>
            <a href="reporte_movimientos_stock.php" class="btn btn-primary">Movimientos stock</a>
            <a href="../conexion_bd/cerrar_sesion.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require_once("../conexion_bd/conexion.php");

// Obtener notificaciones de la base de datos
$query = $pdo->prepare("
    SELECT 
        a.ID_Alerta,
        a.Fecha_Alerta,
        a.Estado,
        a.Descripcion,
        a.Tipo_Alerta,
        p.Nombre as producto,
        p.Stock_Actual,
        p.Stock_Minimo,
        p.Categoria
    FROM alerta_stock a
    JOIN producto p ON a.ID_Producto = p.ID_Producto
    ORDER BY a.Fecha_Alerta DESC
");
$query->execute();
$notificaciones = $query->fetchAll(PDO::FETCH_ASSOC);

// Obtener categorías únicas
$stmt = $pdo->query("SELECT DISTINCT Categoria FROM producto WHERE Categoria IS NOT NULL");
$categorias = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../resource/css/notificacion.css">
</head>
<body>
    <div class="contenedor">
        <h1>Notificaciones</h1>
        <div class="barra-filtros">
            <div class="grupo-filtro">
                <label for="categoriaProducto">Categoría:</label>
                <select id="categoriaProducto" class="desplegable">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo htmlspecialchars($categoria); ?>">
                            <?php echo htmlspecialchars($categoria); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="grupo-filtro">
                <label for="fechaInicio">Desde:</label>
                <input type="date" id="fechaInicio" class="campo-fecha">
            </div>
            <div class="grupo-filtro">
                <label for="fechaFin">Hasta:</label>
                <input type="date" id="fechaFin" class="campo-fecha">
            </div>
            <button id="filtrarNotificaciones" class="boton-filtrar">Filtrar</button>
        </div>

        <div id="seccionNotificaciones" class="seccion-notificaciones">
            <?php foreach ($notificaciones as $notificacion): ?>
                <div class="tarjeta-notificacion" 
                     data-categoria="<?php echo htmlspecialchars($notificacion['Categoria']); ?>"
                     data-fecha="<?php echo $notificacion['Fecha_Alerta']; ?>">
                    <div class="encabezado-notificacion">
                        <span class="tipo-notificacion">
                            <?php echo htmlspecialchars($notificacion['Descripcion']); ?>
                        </span>
                        <span class="categoria-notificacion">
                            <?php echo htmlspecialchars($notificacion['Categoria']); ?>
                        </span>
                        <span class="fecha-notificacion">
                            <?php echo date('d/m/Y', strtotime($notificacion['Fecha_Alerta'])); ?>
                        </span>
                    </div>
                    <div class="cuerpo-notificacion">
                        <p><strong>Producto:</strong> <?php echo htmlspecialchars($notificacion['producto']); ?></p>
                        <p><strong>Stock Actual:</strong> <?php echo $notificacion['Stock_Actual']; ?> unidades</p>
                        <p><strong>Stock Mínimo:</strong> <?php echo $notificacion['Stock_Minimo']; ?> unidades</p>
                        <?php if ($notificacion['Estado'] == 'Pendiente'): ?>
                            <button class="boton-atender" 
                                    onclick="atenderNotificacion(<?php echo $notificacion['ID_Alerta']; ?>)">
                                Marcar como atendida
                            </button>
                        <?php else: ?>
                            <span class="estado-atendido">Atendida</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="../Models/notificacion.js"></script>
</body>
</html> 
<?php
require_once("../conexion_bd/reporte_compras_proveedor.php");

$resultados = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $resultados = obtenerComprasPorProveedor($fecha_inicio, $fecha_fin);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Compras por Proveedor</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Reporte de Compras por Proveedor</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="fecha_fin" class="form-label">Fecha fin:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Generar Reporte</button>
    </form>

    <?php if (!empty($resultados)): ?>
        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th>Proveedor</th>
                    <th>Producto</th>
                    <th>Fecha Movimiento</th>
                    <th>Cantidad</th>
                    <th>Tipo Movimiento</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultados as $fila): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fila['Proveedor']); ?></td>
                        <td><?php echo htmlspecialchars($fila['Producto']); ?></td>
                        <td><?php echo htmlspecialchars($fila['Fecha_Movimiento']); ?></td>
                        <td><?php echo htmlspecialchars($fila['Cantidad']); ?></td>
                        <td><?php echo htmlspecialchars($fila['Tipo_Movimiento']); ?></td>
                        <td><?php echo htmlspecialchars($fila['Observaciones']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Botón para generar PDF -->
        <form action="../conexion_bd/exportar_compras_pdf.php" method="post" target="_blank" class="mt-3">
            <input type="hidden" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
            <input type="hidden" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>">
            <button type="submit" class="btn btn-danger">Generar PDF</button>
        </form>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p class="text-center text-danger mt-4">No se encontraron resultados para el rango de fechas especificado.</p>
    <?php endif; ?>
</div>
</body>
</html>

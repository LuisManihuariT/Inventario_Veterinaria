<?php
require_once("../conexion_bd/reporte_movimientos_stock.php");

$resultados = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // Obtener los resultados de los movimientos de stock
    $resultados = obtenerMovimientosStock($fecha_inicio, $fecha_fin);

    // Si se desea generar el PDF, se puede hacer aquí
    if (isset($_POST['generar_pdf'])) {
        generarPDF($fecha_inicio, $fecha_fin, $resultados);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Movimientos de Stock</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Reporte de Movimientos de Stock</h2>
        
        <!-- Formulario para seleccionar el rango de fechas -->
        <form method="POST">
            <div class="row mb-3">
                <div class="col">
                    <label for="fecha_inicio" class="form-label">Fecha de inicio:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
                </div>
                <div class="col">
                    <label for="fecha_fin" class="form-label">Fecha de fin:</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Generar Reporte</button>
        </form>

        <?php if (!empty($resultados)): ?>
            <!-- Tabla con los resultados -->
            <table class="table table-bordered table-striped mt-4">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Usuario</th>
                        <th>Tipo Movimiento</th>
                        <th>Cantidad</th>
                        <th>Fecha Movimiento</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $fila): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fila['Producto']); ?></td>
                            <td><?php echo htmlspecialchars($fila['Usuario']); ?></td>
                            <td><?php echo htmlspecialchars($fila['Tipo_Movimiento']); ?></td>
                            <td><?php echo htmlspecialchars($fila['Cantidad']); ?></td>
                            <td><?php echo htmlspecialchars($fila['Fecha_Movimiento']); ?></td>
                            <td><?php echo htmlspecialchars($fila['Observaciones']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Botón para generar el PDF -->
            <form method="POST">
                <input type="hidden" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
                <input type="hidden" name="fecha_fin" value="<?php echo $fecha_fin; ?>">
                <button type="submit" name="generar_pdf" class="btn btn-success mt-3">Generar PDF</button>
            </form>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <p class="text-center text-danger mt-4">No se encontraron resultados para el rango de fechas especificado.</p>
        <?php endif; ?>
    </div>
</body>
</html>

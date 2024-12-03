<?php
// Incluir la conexión a la base de datos
require_once 'conexion.php'; // Asegúrate de que la ruta es correcta

// Consulta para obtener los productos
$query = "SELECT * FROM producto"; // Asegúrate de que el nombre de la tabla sea correcto
$stmt = $pdo->prepare($query);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

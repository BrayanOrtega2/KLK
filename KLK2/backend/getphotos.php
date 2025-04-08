<?php
include 'config.php';

// Crear instancia de la clase y obtener conexión
$dbConfig = new DbConfig();
$conn = $dbConfig->getConnection();

// Verificar si la conexión fue exitosa
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}

$sql = "SELECT * FROM posts ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();

$fotos = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($fotos);


?>

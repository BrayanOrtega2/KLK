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
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($photos as &$photo) {
    $sqlComments = "SELECT COUNT(*) as total FROM comments WHERE post_id = :post_id";
    $stmtComments = $conn->prepare($sqlComments);
    $stmtComments->bindParam(':post_id', $photo['id']);
    $stmtComments->execute();
    $result = $stmtComments->fetch(PDO::FETCH_ASSOC);

    $photo['comments'] = $result ? intval($result['total']) : 0;
}


header('Content-Type: application/json');
echo json_encode($photos);


?>

    
    



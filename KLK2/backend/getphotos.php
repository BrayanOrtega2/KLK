<?php
include './config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$dbConfig = new DbConfig();
$conn = $dbConfig->getConnection();

if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}

// Modificar la consulta para incluir el nombre y apellido del usuario
$sql = "SELECT p.*, u.full_name, u.last_name, u.id as user_id
        FROM posts p
        LEFT JOIN users u ON p.user_id = u.id
        ORDER BY p.id DESC";
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

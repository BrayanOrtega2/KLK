<?php
include 'config.php';

$db = new DbConfig();
$conn = $db->getConnection();

header('Content-Type: application/json');

if (isset($_GET['photo_id'])) {
    $photoId = $_GET['photo_id'];

    $stmt = $conn->prepare("SELECT comment FROM comments WHERE post_id = ? ORDER BY created_at DESC");
    $stmt->execute([$photoId]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);


    echo json_encode($comments);
} else {
    echo json_encode([]);
}



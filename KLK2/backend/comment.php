<?php
include 'config.php';

$db = new DbConfig();
$conn = $db->getConnection();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $photoId = $_POST['photo_id'] ?? null;
    $comment = trim($_POST['comment'] ?? '');

    if (!$photoId || $comment === '') {
        echo json_encode([
            'success' => false,
            'message' => 'data is missing'
        ]);
        exit;
    }

    // 
    $stmt = $conn->prepare("INSERT INTO comments (post_id, comment) VALUES (?, ?)");
    if ($stmt->execute([$photoId, $comment])) {
        echo json_encode([
            'success' => true,
            'comment' => $comment 
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error to save at Db'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'disallowed method'
    ]);
}
?>
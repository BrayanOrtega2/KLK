<?php
session_start();
header('Content-Type: application/json');

require_once './config.php';

try {
    $db = new DbConfig();
    $pdo = $db->getConnection();

    if (!isset($_SESSION['user_id']) || !isset($_GET['user1']) || !isset($_GET['user2'])) {
        http_response_code(403);
        echo json_encode(["error" => "Parámetros faltantes o no autorizado"]);
        exit;
    }

    $user1 = $_GET['user1'];
    $user2 = $_GET['user2'];

    $stmt = $pdo->prepare("SELECT m.*, CONCAT(u.full_name, ' ', u.last_name) AS sender_name
                           FROM messages m
                           JOIN users u ON m.sender_id = u.id
                           WHERE (m.sender_id = ? AND m.receiver_id = ?)
                              OR (m.sender_id = ? AND m.receiver_id = ?)
                           ORDER BY m.created_at ASC");
    $stmt->execute([$user1, $user2, $user2, $user1]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(array_map(function ($msg) {
        $msg['created_at_formatted'] = date('H:i', strtotime($msg['created_at']));
        return $msg;
    }, $messages));

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
} 

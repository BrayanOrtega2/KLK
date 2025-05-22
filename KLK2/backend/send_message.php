<?php
session_start();
header('Content-Type: application/json');
require_once './config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $db = new DbConfig();
    $pdo = $db->getConnection();

    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        echo json_encode(["error" => "No autorizado"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['receiver'], $data['content'])) {
        http_response_code(400);
        echo json_encode(["error" => "Datos incompletos"]);
        exit;
    }

    $sender = $_SESSION['user_id'];
    $receiver = $data['receiver'];
    $content = trim($data['content']);

    if ($content === '') {
        http_response_code(400);
        echo json_encode(["error" => "Mensaje vacío"]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, content, status, created_at) VALUES (?, ?, ?, 'sent', NOW())");
    $stmt->execute([$sender, $receiver, $content]);

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
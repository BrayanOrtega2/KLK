<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo "Error: Usuario no autenticado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $userId = $_SESSION['id'];
    $file = $_FILES['photo'];
    $uploadDir = __DIR__ . '/../assets/uploads/'; 
    $filename = time() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        require '../backend/config.php'; 
        $db = new DbConfig();
        $conn = $db->getConnection();

        $sql = "INSERT INTO posts (user_id, image_path, created_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId, $filename]);

        echo "Foto subida exitosamente.";
    } else {
        echo "Error al subir la imagen.";
    }
} else {
    echo "No se recibió ningún archivo.";
}

?>

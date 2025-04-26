<?php
session_start();
require 'config.php';

if (!isset($_SESSION['id'])) {
    echo "Usuario no autenticado";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $photoId = $_POST['id'];

    $dbConfig = new DbConfig();
    $conn = $dbConfig->getConnection();

    // get file name
    $stmt = $conn->prepare("SELECT image_path FROM posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$photoId, $_SESSION['id']]);
    $photo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($photo) {
        $ruta = '../assets/uploads/' . $photo['image_path'];

        // delete local storage
        if (file_exists($ruta)) {
            unlink($ruta);
        }

        // delete in DB
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$photoId]);

        echo "Foto eliminada correctamente.";
    } else {
        echo "No tienes permiso pá";
    }
} else {
    echo "Petición inválida.";
}
?>
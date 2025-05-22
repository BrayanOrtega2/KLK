<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../index.html");
    exit();
}

require_once '../backend/config.php';

$dbConfig = new DbConfig();
$pdo = $dbConfig->getConnection();

$currentUser = $_SESSION['id'];
$currentKlkId = $_SESSION['klk_id'];

// Marcar como leídos los mensajes del contacto actual si hay ID
if (isset($_GET['user_id'])) {
    $contactId = (int) $_GET['user_id'];
    $update = $pdo->prepare("UPDATE messages SET status = 'read', read_at = NOW() WHERE sender_id = ? AND receiver_id = ? AND status = 'sent'");
    $update->execute([$contactId, $currentUser]);
}

// Usuarios con conteo de mensajes no leídos y último mensaje recibido
$query = "
    SELECT u.id, u.full_name, u.last_name,
        (
            SELECT COUNT(*)
            FROM messages m
            WHERE m.sender_id = u.id AND m.receiver_id = :currentUser AND m.status = 'sent'
        ) AS unread,
        (
            SELECT MAX(m2.created_at)
            FROM messages m2
            WHERE (m2.sender_id = u.id AND m2.receiver_id = :currentUser)
               OR (m2.sender_id = :currentUser AND m2.receiver_id = u.id)
        ) AS last_message
    FROM users u
    WHERE u.id != :currentUser AND u.fk_klk_id = :klkId
    ORDER BY last_message DESC
";

$stmt = $pdo->prepare($query);
$stmt->execute([
    ':currentUser' => $currentUser,
    ':klkId' => $currentKlkId
]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KLK-User list</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link rel="stylesheet" href="../css/style7.css">
    
    <link rel="shortcut icon" href="../assets/favicon_io/favicon-32x32.png" type="image/png">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon_io/site.webmanifest">
</head>
<body>
    <nav class="navbar navbar-custom  fixed-top">
        <div class="container d-flex justify-content-between align-items-center" >
            <a href="./home.php" class="btn text-white btn-sm">
                <i class="bi bi-chevron-left"></i>
            </a>
            <span class="navbar-brand mb-0 h6 text-white">User list</span>
            <div style="width: 32px;"></div> 
        </div>
        
    </nav>

    <div class="contact-wrapper" >
        <div class="contact-list">
            <ul class="list-group">
                <?php foreach ($users as $user): ?>
                    <li class="list-group-item">
                        <a href="chat.php?user_id=<?= $user['id'] ?>" class="d-flex align-items-center justify-content-between text-decoration-none text-dark">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-circle fs-4"></i>
                                <span><?= htmlspecialchars($user['full_name'] . ' ' . $user['last_name']) ?></span>
                            </div>
                            <?php if ($user['unread'] > 0): ?>
                                <span class="badge badge-new"><?= $user['unread'] ?></span>
                            <?php else: ?>
                                <i class="bi bi-check2 text-success" title="Sin mensajes nuevos"></i>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>

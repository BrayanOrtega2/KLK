<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.html");
    exit();
}
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = $_SESSION['id'];
}

require_once '../backend/config.php';
$dbConfig = new DbConfig();
$pdo = $dbConfig->getConnection();

$current_id = $_SESSION['id'];
$other_id = $_GET['user_id'];

$stmt = $pdo->prepare("SELECT full_name, last_name FROM users WHERE id = ?");
$stmt->execute([$other_id]);
$otherUser     = $stmt->fetch();

if (!$otherUser    ) {
    echo "Usuario no encontrado";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KLK- <?= htmlspecialchars($otherUser   ['full_name']) ?></title>
 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <link rel="stylesheet" href="../css/style6.css">

  <link rel="shortcut icon" href="../assets/favicon_io/favicon-32x32.png" type="image/png">
  <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon_io/favicon-16x16.png">
  <link rel="manifest" href="../assets/favicon_io/site.webmanifest">
</head>
<body>
  <h2 id="chat-title" data-other-id="<?= $other_id ?>" data-current-id="<?= $current_id ?>">
    <a href="./contacts.php" class="btn text-white ">
    <i class="bi bi-chevron-left"></i>
    </a>
    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($otherUser  ['full_name'] . " " . $otherUser   ['last_name']) ?>
  </h2>

  <div id="chat-box"></div>

  <form id="chat-form">
    <input type="text" id="message" placeholder="write a message">
    <button type="submit">
      <i class="bi bi-send-fill"></i>
    </button>
  </form>

  <script src="../js/chat.js"></script>
</body>
</html>

<?php
require_once './config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $full_name = $first_name;
    $role_id = 1;
    $klk_id = 1;

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    $dbConfig = new DbConfig();
    $db = $dbConfig->getConnection();

    if (!$db) {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo conectar a la base de datos.']);
        exit;
    }

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'El correo ya está registrado.']);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (full_name, last_name, email, password, klk_role_id, fk_klk_id, status, statusChat)
            VALUES (:full_name, :last_name, :email, :password, :role_id, :klk_id, 'Activo', 'Offline')";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':role_id', $role_id);
    $stmt->bindParam(':klk_id', $klk_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Usuario registrado exitosamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar el usuario.']);
    }
}
?>

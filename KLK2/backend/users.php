<?php
require_once './config.php';

class EstaticUsers {
    private $db;

    public function __construct() {
        $dbConfig = new DbConfig();
        $this->db = $dbConfig->getConnection();
    }

    public function createUsers() {
        $users = [
            ["Brayan", "Ortega", "be@os.com", "12", 2, 1],
            ["Adri", "Soto", "adri@sb.com", "345", 2, 1],
            ["Kiki", "Nueve", "km9@rm.com", "mbappe", 2, 1],
        ];

        foreach ($users as $user) {
            $hashedPassword = password_hash($user[3], PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (full_name, last_name, email, password, klk_role_id, fk_klk_id, status, statusChat) 
                    VALUES (:full_name, :last_name, :email, :password, :role_id, :klk_id, 'Activo', 'Offline')";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':full_name', $user[0]);
            $stmt->bindParam(':last_name', $user[1]);
            $stmt->bindParam(':email', $user[2]);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role_id', $user[4]);
            $stmt->bindParam(':klk_id', $user[5]);

            if ($stmt->execute()) {
                echo "Usuario {$user[2]} creado correctamente.<br>";
            } else {
                echo "Error al crear usuario {$user[2]}.<br>";
            }
        }
    }
}

$esUsers = new EstaticUsers();
$esUsers->createUsers();
?>

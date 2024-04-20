<?php
require_once __DIR__ . "/Db.php";

class User {
    protected $db;

    public function __construct() {
        $this->db = new Db();
    }

    public function login($email, $password) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $stmt->execute([$email, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function logout() {
        // Logout logic
    }
}
?>

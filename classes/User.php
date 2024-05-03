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
    public function addManager($email, $password, $first_name, $last_name, $location_id) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, type_user, location_id) VALUES (?, ?, ?, ?, 'manager', ?)");
        $result = $stmt->execute([$email, $password, $first_name, $last_name, $location_id]);
        return $result;
    }
}
?>
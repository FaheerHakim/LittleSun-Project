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

    public function addManager($email, $password, $first_name, $last_name, $location_id) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, type_user, location_id) VALUES (?, ?, ?, ?, 'manager', ?)");
        $result = $stmt->execute([$email, $password, $first_name, $last_name, $location_id]);
        return $result;
    }
    public function assignTaskType($userId, $taskTypeId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO user_task_types (user_id, task_type_id) VALUES (?, ?)");
        $result = $stmt->execute([$userId, $taskTypeId]);
        return $result;
    }

    // Get assigned task types for a user
    public function getAssignedTaskTypes($userId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT taskTypes.* FROM taskTypes INNER JOIN user_task_types ON taskTypes.id = user_task_types.task_type_id WHERE user_task_types.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsers() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getEmployeeUsers() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE type_user = 'employee'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
// Remove the closing PHP tag
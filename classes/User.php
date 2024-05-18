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

    
    public function addManager($email, $password, $first_name, $last_name, $location_id, $file) {
        $conn = $this->db->getConnection();
        
        // Handle file upload
        if ($file["error"] == 0) {
            $target_dir = "images/";
            $target_file = $target_dir . basename($file["name"]);
            if (!move_uploaded_file($file["tmp_name"], $target_file)) {
                // Error uploading file
                return false;
            }
        } else {
            // Error uploading file
            return false;
        }

        // Insert manager with profile picture into database
        $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, type_user, location_id, profile_picture) VALUES (?, ?, ?, ?, 'manager', ?, ?)");
        $result = $stmt->execute([$email, $password, $first_name, $last_name, $location_id, $target_file]);
        return $result;
    }

    public function addEmployee($email, $password, $first_name, $last_name, $location_id, $file) {
        $conn = $this->db->getConnection();
        
        // Handle file upload
        if ($file["error"] == 0) {
            $target_dir = "images/";
            $target_file = $target_dir . basename($file["name"]);
            if (!move_uploaded_file($file["tmp_name"], $target_file)) {
                // Error uploading file
                return false;
            }
        } else {
            // Error uploading file
            return false;
        }

        // Insert employee with profile picture into database
        $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, type_user, location_id, profile_picture) VALUES (?, ?, ?, ?, 'employee', ?, ?)");
        $result = $stmt->execute([$email, $password, $first_name, $last_name, $location_id, $target_file]);
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
        $stmt = $conn->prepare("SELECT task_types.* FROM task_types INNER JOIN user_task_types ON task_types.task_type_id = user_task_types.task_type_id WHERE user_task_types.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers() {
        $conn = $this->db->getConnection();
        $stmt = $conn->query("SELECT user_id, first_name, last_name FROM users WHERE type_user = 'employee'");
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

    public function getManagerUsers() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE type_user = 'manager'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function updateUserPassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); // Hash the password
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->execute([$hashedPassword, $userId]);
        return $stmt->rowCount() > 0;
    }
    
    public function getUserById($userId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
        
    public function removeTaskTypeAssignment($userId, $taskTypeId) {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("DELETE FROM user_task_types WHERE user_id = ? AND task_type_id = ?");
            $stmt->execute([$userId, $taskTypeId]);
            return true; // Assignment removed successfully
        } catch (PDOException $e) {
            // Handle the error (e.g., log it, display a message, etc.)
            return false; // Failed to remove assignment
        }
    }

    public function getUserNameById($userId) {
        $userDetails = $this->getUserById($userId);
        return $userDetails['first_name'] . ' ' . $userDetails['last_name'];
    }
}
    

// Remove the closing PHP tag
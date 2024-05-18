<?php
require_once __DIR__ . "/Db.php";

class TaskType {
    protected $db;

    public function __construct() {
        $this->db = new Db();
    }

    public function addTaskType($typeName) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO task_types (task_type_name) VALUES (?)");
        $stmt->execute([$typeName]);
        return $conn->lastInsertId();
    }

    public function deleteTaskType($typeId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("DELETE FROM task_types WHERE task_type_id = ?");
        $stmt->execute([$typeId]);
        return $stmt->rowCount() > 0;
    }

    public function getTaskTypes() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM task_types");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTaskTypeNameById($typeId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM task_types WHERE task_type_id = ?");
        $stmt->execute([$typeId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function updateTaskType($typeId, $newName) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("UPDATE task_types SET task_type_name = ? WHERE task_type_id = ?");
        $stmt->execute([$newName, $typeId]);
        return $stmt->rowCount() > 0;
    }
    public function getAllTaskTypes() {
        $conn = $this->db->getConnection();
        $stmt = $conn->query("SELECT task_type_id, task_type_name FROM task_types");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}

?>
<?php
require_once __DIR__ . "/Db.php";

class TaskType {
    protected $db;

    public function __construct() {
        $this->db = new Db();
    }

    public function addTaskType($typeName) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO taskTypes (task_type_name) VALUES (?)");
        $stmt->execute([$typeName]);
        return $conn->lastInsertId();
    }

    public function deleteTaskType($typeId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("DELETE FROM taskTypes WHERE task_type_id = ?");
        $stmt->execute([$typeId]);
        return $stmt->rowCount() > 0;
    }

    public function getTaskTypes() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM taskTypes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
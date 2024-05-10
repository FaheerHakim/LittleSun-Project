<?php
require_once __DIR__ . "/Db.php";

class Schedule {
    protected $db;

    public function __construct() {
        $this->db = new Db();
    }

    public function assignTaskSchedule($userId, $locationId, $taskTypeId, $startTime, $endTime, $date) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO work_schedule (user_id, location_id, task_type_id, start_time, end_time, date) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$userId, $locationId, $taskTypeId, $startTime, $endTime, $date]);
    }

    public function getTimeOffRequests($userId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM time_off_request WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEmployeeUsers() {
        $conn = $this->db->getConnection();
        $stmt = $conn->query("SELECT * FROM users WHERE type_user = 'employee'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTaskTypes() {
        $conn = $this->db->getConnection();
        $stmt = $conn->query("SELECT * FROM task_types");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLocations() {
        $conn = $this->db->getConnection();
        $stmt = $conn->query("SELECT * FROM locations");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

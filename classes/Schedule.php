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

    public function hasWorkSchedule($userId, $taskTypeId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) FROM work_schedule WHERE user_id = ? AND task_type_id = ?");
        $stmt->execute([$userId, $taskTypeId]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }
    public function getWorkScheduleForDate($date) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM work_schedule WHERE date = ?");
        $stmt->execute([$date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getWorkScheduleForPeriod($startDate, $endDate, $userId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM work_schedule WHERE user_id = ? AND date BETWEEN ? AND ?");
        $stmt->execute([$userId, $startDate, $endDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function getPlannedWorkingHours($userId, $date) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT TIMESTAMPDIFF(HOUR, start_time, end_time) AS planned_hours 
                                FROM work_schedule 
                                WHERE user_id = ? AND DATE(date) = ?");
        $stmt->execute([$userId, $date]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['planned_hours'] : null;
    }


    public function getWorkScheduleForLocation($startDate, $endDate, $locationId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM work_schedule WHERE date BETWEEN ? AND ? AND location_id = ?");
        $stmt->execute([$startDate, $endDate, $locationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
?>

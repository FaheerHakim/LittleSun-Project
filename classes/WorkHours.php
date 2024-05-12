<?php

require_once __DIR__ . "/Db.php";

class WorkHours {
    protected $db;

    public function __construct() {
        $this->db = new Db();
    }

    public function hasClockedInToday($userId) {
        $conn = $this->db->getConnection();
        $date = date("Y-m-d");
        $stmt = $conn->prepare("SELECT COUNT(*) FROM work_hours WHERE user_id = ? AND DATE(start_time) = ?");
        $stmt->execute([$userId, $date]);
        return $stmt->fetchColumn() > 0;
    }

    public function clockIn($userId, $startTime) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO work_hours (user_id, start_time) VALUES (?, ?)");
        $stmt->execute([$userId, $startTime]);
    }

    public function hasClockedOutToday($userId) {
        $conn = $this->db->getConnection();
        $date = date("Y-m-d");
        $stmt = $conn->prepare("SELECT COUNT(*) FROM work_hours WHERE user_id = ? AND DATE(end_time) = ?");
        $stmt->execute([$userId, $date]);
        return $stmt->fetchColumn() > 0;
    }

    public function clockOut($userId, $endTime) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("UPDATE work_hours SET end_time = ? WHERE user_id = ? AND end_time IS NULL");
        $stmt->execute([$endTime, $userId]);
    }

    public function getClockInTimes($userId, $date) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT start_time FROM work_hours WHERE user_id = ? AND DATE(start_time) = ?");
        $stmt->execute([$userId, $date]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function getClockOutTimes($userId, $date) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT end_time FROM work_hours WHERE user_id = ? AND DATE(end_time) = ?");
        $stmt->execute([$userId, $date]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAllWorkHours() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT user_id, start_time, end_time FROM work_hours");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getWorkHoursForUser($userId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT start_time, end_time FROM work_hours WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllUserIds() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT user_id FROM work_hours");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>

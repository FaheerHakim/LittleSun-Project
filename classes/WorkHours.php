<?php
// WorkHours.php

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
        $stmt = $conn->prepare("UPDATE work_hours SET end_time = ? WHERE user_id = ? AND DATE(start_time) = DATE(?)");
        $stmt->execute([$endTime, $userId, $endTime]);
    }
}
?>

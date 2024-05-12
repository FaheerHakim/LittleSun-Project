<?php
require_once __DIR__ . "/Db.php";

class TimeOff {
    protected $db;

    public function __construct() {
        $this->db = new Db();
    }

    public function requestTimeOff($userId, $startDate, $endDate, $reason) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO time_off_requests (user_id, start_date, end_date, reason, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->execute([$userId, $startDate, $endDate, $reason]); // Pass the reason to the execute method
        return $conn->lastInsertId();
    }

    public function getTimeOffRequests() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM time_off_requests");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function approveTimeOffRequest($requestId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("UPDATE time_off_requests SET status = 'approved' WHERE time_off_request_id = ?");
        $stmt->execute([$requestId]);
        return $stmt->rowCount() > 0;
    }
    
    
    public function declineTimeOffRequest($requestId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("UPDATE time_off_requests SET status = 'declined' WHERE time_off_request_id = ?");
        $stmt->execute([$requestId]);
        return $stmt->rowCount() > 0;
    }
    public function hasApprovedTimeOff($userId, $date) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) FROM time_off_requests WHERE user_id = ? AND start_date <= ? AND end_date >= ? AND status = 'approved'");
        $stmt->execute([$userId, $date, $date]);
        return $stmt->fetchColumn() > 0;
    }
    public function getApprovedTimeOffDetails($userId, $date) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT start_date, end_date FROM time_off_requests WHERE user_id = ? AND start_date <= ? AND end_date >= ? AND status = 'approved'");
        $stmt->execute([$userId, $date, $date]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

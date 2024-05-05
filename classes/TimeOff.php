<?php
require_once __DIR__ . "/Db.php";

class TimeOff {
    protected $db;

    public function __construct() {
        $this->db = new Db();
    }

    public function requestTimeOff($userId, $startDate, $endDate) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO time_off_requests (user_id, start_date, end_date, status) VALUES (?, ?, ?, 'pending')");
        $stmt->execute([$userId, $startDate, $endDate]);
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
    
    
}
?>

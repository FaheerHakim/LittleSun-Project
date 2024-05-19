<?php
require_once __DIR__ . "/Db.php";

class TimeOff {
    protected $db;

    public function __construct() {
        $this->db = new Db();
    }

    public function requestTimeOff($userId, $startDate, $endDate, $reason, $additionalNotes) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO time_off_requests (user_id, start_date, end_date, reason, additional_notes, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$userId, $startDate, $endDate, $reason, $additionalNotes]);
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
        $stmt = $conn->prepare("SELECT start_date, end_date, reason, additional_notes FROM time_off_requests WHERE user_id = ? AND start_date <= ? AND end_date >= ? AND status = 'approved'");
        $stmt->execute([$userId, $date]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getApprovedTimeOffRequests($startDate, $endDate) {
    $conn = $this->db->getConnection();
    $stmt = $conn->prepare("SELECT user_id, start_date, end_date, reason FROM time_off_requests WHERE status = 'approved' AND start_date <= ? AND end_date >= ?");
    $stmt->execute([$endDate, $startDate]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function executeCustomQuery($query) {
    $conn = $this->db->getConnection();
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}


?>

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
    public function clockOut($userId, $endTime) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("UPDATE work_hours SET end_time = ? WHERE user_id = ? AND end_time IS NULL");
        $stmt->execute([$endTime, $userId]);
    }

    public function hasClockedOutToday($userId) {
        $conn = $this->db->getConnection();
        $date = date("Y-m-d");
        $stmt = $conn->prepare("SELECT COUNT(*) FROM work_hours WHERE user_id = ? AND DATE(end_time) = ?");
        $stmt->execute([$userId, $date]);
        return $stmt->fetchColumn() > 0;
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
      public function getWorkHoursForMonth($userId, $month) {
        $conn = $this->db->getConnection();
        $startOfMonth = date('Y-m-01', strtotime($month));
        $endOfMonth = date('Y-m-t', strtotime($month));
        $stmt = $conn->prepare("SELECT start_time, end_time, TIMESTAMPDIFF(SECOND, start_time, end_time) / 3600 AS total_hours FROM work_hours WHERE user_id = ? AND start_time >= ? AND end_time <= ?");
        $stmt->execute([$userId, $startOfMonth, $endOfMonth]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getFilteredWorkHours($startDate, $endDate, $personId, $taskTypeId, $includeOvertime) {
        $conn = $this->db->getConnection();
        $query = "SELECT start_time, end_time, TIMESTAMPDIFF(SECOND, start_time, end_time) / 3600 AS total_hours 
                  FROM work_hours 
                  WHERE 1=1";
    
        $params = [];
    
        // Add filters to the query
        if ($startDate && $endDate) {
            $query .= " AND DATE(start_time) >= ? AND DATE(end_time) <= ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }
        if ($personId) {
            $query .= " AND user_id = ?";
            $params[] = $personId;
        }
        if ($taskTypeId) {
            $query .= " AND task_type_id = ?";
            $params[] = $taskTypeId;
        }
        if (!$includeOvertime) {
            $query .= " AND overtime = 0";
        }
    
        // Prepare and execute the query
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
    
        // Fetch and return the filtered work hours data
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function executeCustomQuery($query) {
        $conn = $this->db->getConnection();
        $stmt = $conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

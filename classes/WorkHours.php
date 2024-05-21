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
    
        
        $stmt = $conn->prepare("SELECT start_time AS planned_start_time, end_time AS planned_end_time
                                FROM work_schedule 
                                WHERE user_id = ? AND DATE(start_time) = DATE(?)");
        $stmt->execute([$userId, $endTime]);
        $scheduleData = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$scheduleData) {
            
            return;
        }
    
        $plannedStartTime = new DateTime($scheduleData['planned_start_time']);
        $plannedEndTime = new DateTime($scheduleData['planned_end_time']);
    
       
        $plannedWorkSeconds = $plannedEndTime->getTimestamp() - $plannedStartTime->getTimestamp();
        $plannedWorkHours = $plannedWorkSeconds / 3600; 
    
       
        $startOfDay = date("Y-m-d 00:00:00", strtotime($endTime));
        $endOfDay = date("Y-m-d 23:59:59", strtotime($endTime));
        $stmt = $conn->prepare("SELECT SUM(TIMESTAMPDIFF(SECOND, start_time, end_time)) AS total_seconds 
                                FROM work_hours 
                                WHERE user_id = ? AND start_time BETWEEN ? AND ?");
        $stmt->execute([$userId, $startOfDay, $endOfDay]);
        $totalSecondsWorked = $stmt->fetchColumn();
    
   
        $actualWorkHours = $totalSecondsWorked / 3600; 
    
 
        $overtimeDuration = max($actualWorkHours - $plannedWorkHours, 0);
    
        
        if ($overtimeDuration > 0) {
            try {
                $stmt = $conn->prepare("INSERT INTO work_hours (user_id, start_time, end_time, overtime) VALUES (?, ?, ?, ?)");
                $stmt->execute([$userId, $endTime, $endTime, $overtimeDuration]);
            } catch (PDOException $e) {
             
                error_log('Error inserting overtime data: ' . $e->getMessage());
            }
        }
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
    
      
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     public function executeCustomQuery($query) {
        $conn = $this->db->getConnection();
        $stmt = $conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function insertOvertime($userId, $startTime, $endTime, $overtimeDuration) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO overtime (user_id, start_time, end_time, overtime_duration) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $startTime, $endTime, $overtimeDuration]);
    }
    public function getWorkScheduleForUserAndDate($userId, $date) {
        $conn = $this->db->getConnection();
        $startOfDay = date("Y-m-d 00:00:00", strtotime($date));
        $endOfDay = date("Y-m-d 23:59:59", strtotime($date));
        $stmt = $conn->prepare("SELECT * FROM work_schedule WHERE user_id = ? AND start_time >= ? AND end_time <= ?");
        $stmt->execute([$userId, $startOfDay, $endOfDay]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function isClockedIn($userId) {
        $conn = $this->db->getConnection();
        $date = date("Y-m-d");
        $stmt = $conn->prepare("SELECT COUNT(*) FROM work_hours WHERE user_id = ? AND DATE(start_time) = ? AND end_time IS NULL");
        $stmt->execute([$userId, $date]);
        return $stmt->fetchColumn() > 0;
    }
}
?>

<?php
require_once __DIR__ . "/Db.php";

class Report {
    protected $db;

    public function __construct() {
        $this->db = new Db();
    }
    public function getLocations() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM locations");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to fetch users for filter options
    public function getUsers() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to fetch task types for filter options
    public function getTaskTypes() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM task_types");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function generateReport($locationId, $userId, $taskTypeId, $overtime) {
        $conn = $this->db->getConnection();
        $query = "SELECT users.first_name, users.last_name, locations.city, task_types.task_type_name, work_hours.start_time, work_hours.end_time, work_hours.overtime 
                  FROM work_hours 
                  JOIN users ON work_hours.user_id = users.user_id 
                  JOIN locations ON work_hours.location_id = locations.location_id 
                  JOIN task_types ON work_hours.task_type_id = task_types.task_type_id 
                  WHERE 1";

        $params = [];

        if (!empty($locationId)) {
            $query .= " AND locations.location_id = ?";
            $params[] = $locationId;
        }
        if (!empty($userId)) {
            $query .= " AND users.user_id = ?";
            $params[] = $userId;
        }
        if (!empty($taskTypeId)) {
            $query .= " AND task_types.task_type_id = ?";
            $params[] = $taskTypeId;
        }
        if ($overtime !== null) {
            $query .= " AND work_hours.overtime = ?";
            $params[] = $overtime;
        }

        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

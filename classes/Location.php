<?php
require_once __DIR__ . "/Db.php";

class Location {
    protected $db;

    public function __construct() {
        $this->db = new Db();
    }

    public function addLocation($locationName) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO locations (city) VALUES (?)");
        $stmt->execute([$locationName]);
        return $conn->lastInsertId();
    }
    
    public function updateLocation($userId, $locationId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("UPDATE users SET location_id = ? WHERE id = ?");
        $stmt->execute([$locationId, $userId]);
        return $stmt->rowCount();
    }
}
?>

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

    public function getExistingLocations() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT city FROM locations");
        $stmt->execute();
        $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $cityNames = array_column($locations, 'city');
        return $cityNames;
    }
}
?>
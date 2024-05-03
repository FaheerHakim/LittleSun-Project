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
        $stmt = $conn->prepare("UPDATE users SET location_id = ? WHERE user_id = ?");
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

    public function getLocationIdByName($locationName) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT location_id FROM locations WHERE city = ?");
        $stmt->execute([$locationName]);
        $location = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($location) {
            return $location['location_id'];
        } else {
            return null;
        }
    }

    public function deleteLocation($locationId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("DELETE FROM locations WHERE location_id = ?");
        $stmt->execute([$locationId]);
        return $stmt->rowCount() > 0;
    }
}
?>

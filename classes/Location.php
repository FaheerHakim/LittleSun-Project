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
    public function getLocationNameById($locationId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM locations WHERE location_id = ?");
        $stmt->execute([$locationId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function updateLocation($locationId, $newLocationName) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("UPDATE locations SET city = ? WHERE location_id = ?");
        $stmt->execute([$newLocationName, $locationId]);
        return $stmt->rowCount() > 0;
    }
    public function getAllLocations() {
        $conn = $this->db->getConnection();
        $stmt = $conn->query("SELECT location_id, city FROM locations");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

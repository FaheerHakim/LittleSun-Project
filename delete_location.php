<?php
session_start();
require_once __DIR__ . "/classes/Db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['location_id']) && !empty($_POST['location_id'])) {
        $locationId = $_POST['location_id'];
        
        // Database connection
        $db = new Db();
        $conn = $db->getConnection();
        
        // Prepare and execute SQL query to delete the location
        $stmt = $conn->prepare("DELETE FROM locations WHERE location_id = ?");
        $stmt->execute([$locationId]);
        
        echo "Location deleted successfully.";
    } else {
        echo "Location ID is required.";
    }
} else {
    echo "Invalid request.";
}
?>

<?php
require_once __DIR__ . "/User.php";

class Manager extends User {
    public function __construct() {
        parent::__construct();
    }

    public function add_manager ($email, $password, $first_name, $last_name, $location) {
        $conn = $this->db->getConnection();
        
        // Correct the SQL query
        $stmt = $conn->prepare("INSERT INTO managers (email, password, first_name, last_name, location) VALUES (?, ?, ?, ?, ?)");

        // Execute with correct parameters
        $stmt->execute([$email, $password, $first_name, $last_name, $location]);

        // Fetch results if needed (for insert operations, this might not be applicable)
        // It's likely you don't need to fetch any results after an INSERT operation
        // If you want to return the inserted data, a different query is needed
        
        // Check if the operation succeeded
        if ($stmt) {
            echo "New manager added successfully.";
        } else {
            echo "Error: " ;
        }
    }
}
?>

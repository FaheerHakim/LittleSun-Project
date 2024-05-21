<?php
require_once __DIR__ . "/User.php";

class Manager extends User {
    public function __construct() {
        parent::__construct();
    }

    public function add_manager ($email, $password, $first_name, $last_name, $location) {
        $conn = $this->db->getConnection();
        
        $stmt = $conn->prepare("INSERT INTO managers (email, password, first_name, last_name, location) VALUES (?, ?, ?, ?, ?)");

        $stmt->execute([$email, $password, $first_name, $last_name, $location]);

       
        if ($stmt) {
            echo "New manager added successfully.";
        } else {
            echo "Error: " ;
        }
    }
}
?>

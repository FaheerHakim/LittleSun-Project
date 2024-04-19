<?php
class Database {
    private static $instance;
    private $conn;

    // Private constructor to prevent instantiation from outside
    private function __construct() {
        $conn = new mysqli("localhost", "user", "root", "little_sun");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        else {
            echo "Database connection successful!";
        }       
    }
    
    public function getConnection() {
        return $this->conn;
    }

}

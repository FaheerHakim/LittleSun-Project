<?php
class Db {
    private static $conn;

    public static function connect(){
        try {
            if(self::$conn == null){ 
                self::$conn = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "Connected to database"; // Optional, for debugging
            }
            return self::$conn;
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit(); // Terminate script execution
        }
    }
}



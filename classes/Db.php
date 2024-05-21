<?php
class Db {
    private $host = 'ID437071_littlesun.db.webhosting.be';
    private $user = 'ID437071_littlesun';
    private $password = 'Thomasmore2023';
    private $dbname = 'ID437071_littlesun';
    private $conn;

    public function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        try {
            $this->conn = new PDO($dsn, $this->user, $this->password, $options);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>

<?php
require_once __DIR__ . "/User.php";

class Admin extends User {
    public function __construct() {
        parent::__construct();
    }

    // Additional admin functionalities can be added here
}
?>

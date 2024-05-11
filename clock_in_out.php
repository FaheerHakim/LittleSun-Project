<?php
// Include database connection and User class
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'logged_in.php'; // Check if user is logged in
include 'permission_employee.php'; 
include 'classes/User.php';

$user = new User();

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $userId = 1; // Assuming user ID is 1

    switch ($action) {
        case 'clockIn':
            $user->clockIn($userId);
            echo "Clocked in successfully!";
            break;
        
        case 'clockOut':
            $user->clockOut($userId);
            echo "Clocked out successfully!";
            break;
        
        default:
            echo "Invalid action!";
            break;
    }
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Clock In/Out</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script/clock_in_out.js"></script>
   
</head>
<body>
    <button id="clockInBtn">Clock In</button>
    <button id="clockOutBtn">Clock Out</button>
    <p id="currentTime">current Time</p>

</body>
</html>

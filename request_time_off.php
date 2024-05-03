<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/TimeOff.php";

include 'logged_in.php';

include 'permission_employee.php';

// Instantiate TimeOff class
$timeOffHandler = new TimeOff();

// Process time off request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $userId = $_SESSION['user']['user_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    
    // Request time off
    $timeOffHandler->requestTimeOff($userId, $startDate, $endDate);
    // Optionally, provide feedback to the user
    echo "Time off requested successfully.";
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Time Off</title>
</head>
<body>
    <h1>Request Time Off</h1>
    
    <form action="request_time_off.php" method="post">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date">
        
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date">
        
        <button type="submit">Submit Request</button>
    </form>
</body>
</html>
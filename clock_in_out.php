<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'logged_in.php';
include 'permission_employee.php';

require_once __DIR__ . "/classes/WorkHours.php"; // Include the WorkHours class

// Create an instance of the WorkHours class
$workHoursHandler = new WorkHours();

// Get the user ID from the session (assuming the user is logged in)
$user = $_SESSION['user'];
$userId = $user['user_id']; // Assuming user_id is stored in the 'user_id' key of the user array

// Check if the clock-in button is clicked
if (isset($_POST['clock_in'])) {
    // Clock in the user for the day
    $currentTime = date("Y-m-d H:i:s");
    $workHoursHandler->clockIn($userId, $currentTime);
    echo "You have successfully clocked in for today.";
}

// Check if the clock-out button is clicked
if (isset($_POST['clock_out'])) {
    // Clock out the user for the day
    $currentTime = date("Y-m-d H:i:s");
    $workHoursHandler->clockOut($userId, $currentTime);
    echo "You have successfully clocked out for today.";
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
<form action="clock_in_out.php" method="post">
    <button type="submit" name="clock_in">Clock In</button>
    <button type="submit" name="clock_out">Clock Out</button>
    <p id="currentTime">current Time</p>
</form>
</body>
</html>

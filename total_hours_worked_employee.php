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

// Retrieve all start and end times for the user
$startTimes = $workHoursHandler->getClockInTimes($userId, date("Y-m-d"));
$endTimes = $workHoursHandler->getClockOutTimes($userId, date("Y-m-d"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Work Hours</title>
    <link rel="stylesheet" href="styles/total_worked_hours.css">
</head>
<body>
    <!-- Your HTML content to display start and end times -->
    <a href="dashboard.php" id="goback-button">Go Back</a>

    <div class="container">
        <div class="column">
            <h2>Start Times</h2>
            <ul>
                <?php foreach ($startTimes as $startTime): ?>
                    <li><?= $startTime ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="column">
            <h2>End Times</h2>
            <ul>
                <?php foreach ($endTimes as $endTime): ?>
                    <li><?= $endTime ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}
.container {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}
.column {
    flex: 1;
    padding: 0 10px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
h2 {
    margin-top: 0;
    text-align: center;
}
ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}
li {
    margin-bottom: 5px;
    padding: 5px;
    border-bottom: 1px solid #ddd;
}


    </style>
</body>
</html>

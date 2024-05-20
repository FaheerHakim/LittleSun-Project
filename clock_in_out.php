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

date_default_timezone_set('Europe/Brussels');


$isClockedIn = $workHoursHandler->isClockedIn($userId);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Clock In/Out</title>
    <link rel="stylesheet" href="styles/clock_in_out.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script/clock_in_out.js"></script>
</head>
<body>
<a href="dashboard.php" class="go-back-button" type="button">Go Back</a>
<h1>Clock In/Out</h1>
<div class="container">
    <div class="form-container">
        <form action="clock_in_out.php" method="post">
            <p id="currentTime"></p>
            <?php if ($isClockedIn): ?>
                <button type="submit" name="clock_out">Clock Out</button>
            <?php else: ?>
                <button type="submit" name="clock_in">Clock In</button>
            <?php endif; ?>
        </form>
   <?php
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
        }?>
    </div>
</div>
</body>
</html>

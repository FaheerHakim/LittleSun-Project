<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/TimeOff.php";

include 'logged_in.php';

include 'permission_employee.php';


$timeOffHandler = new TimeOff();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $userId = $_SESSION['user']['user_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $reason = $_POST['reason']; 
    $additionalNotes = $_POST['additional_notes'];
    $timeOffHandler->requestTimeOff($userId, $startDate, $endDate, $reason, $additionalNotes);
    $_SESSION['message'] = "Time off request sent succesfully.";
    $_SESSION['message_type'] = "success";
    header("Location: message-time-off-employee.php");
    exit();
}


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Time Off</title>
    <link rel="stylesheet" href="styles/request_time_off.css">
    <script src="script/request_time_off.js" defer></script>
</head>
<body>
<a href="dashboard.php" class="go-back-button" type="button">Go Back</a>

<h1>Request Time Off</h1>
<div class="container">
<div class="form-container">
    <form action="request_time_off.php" method="post">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" id="start_date">

            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" id="end_date">

            <label for="reason">Reason:</label>
            <select name="reason" id="reason" required>
                <option value="" disabled selected>Select a reason</option>
                <option value="Vacation">Vacation</option>
                <option value="Sick Leave">Sick Leave</option>
                <option value="Personal Time">Personal Time</option>
                <option value="Other">Other</option>
            </select>

            <label for="additional_notes">Additional Notes:</label>
            <textarea name="additional_notes" id="additional_notes" rows="4" cols="50"></textarea>

            <button type="submit">Submit Request</button>
        </form>
        </div>
</div>
</body>

</html>
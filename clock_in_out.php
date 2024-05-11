<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";

include 'logged_in.php';

include 'permission_employee.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   

    $user_id = $_SESSION['user']['id']; // Assuming the user ID is stored in session
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $elapsed_time = $_POST['elapsed_time'];

    $user = new User(); // Assuming this is your user class
    $success = $user->recordTime($user_id, $start_time, $end_time, $elapsed_time); // Call the function to record time

    // You might want to check the success status and return a response or redirect
    if ($success) {
        echo "Time record saved successfully.";
    } else {
        echo "Failed to save time record.";
    }
}

?>




<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <title>Timer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="script/clock_in_out.js"></script>
    
</head>
<body>

<?php
$currentDate = date("d/m/Y"); // Current date
$startTime = isset($_SESSION['start_time']) ? date("H:i:s", strtotime($_SESSION['start_time'])) : "Not started yet";
$elapsedTime = isset($_SESSION['elapsed_time']) ? $_SESSION['elapsed_time'] : "00:00:00";
?>

<div class="timer-container">
    <div class="timer-display" id="currentDateDisplay">Date: <?php echo $currentDate; ?></div>
    <div class="timer-display" id="startTimeDisplay">Start Time: <?php echo $startTime; ?></div>
    <div class="timer-display" id="elapsedTimeDisplay">Elapsed Time: <?php echo $elapsedTime; ?></div>
    <div class="timer-buttons">
        <button id="startButton" onclick="startTimer()">Start</button>
        <button id="stopButton" onclick="stopTimer()" disabled>Stop</button>
    </div>
</div>


    <style>
.timer-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f5f5f5;
    flex-direction: column;
    align-items: center;
}

.timer-display {
    font-size: 1.5em;
    margin-bottom: 10px;
}

.timer-buttons {
    display: flex;
    gap: 10px; /* Spacing between buttons */
}

.timer-buttons button {
    padding: 10px 20px;
    font-size: 1em;
    border: none;
    background-color: #4CAF50;
    color: white;
    cursor: pointer;
    border-radius: 5px;
}

.timer-buttons button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}
    </style>
</body>

</html>
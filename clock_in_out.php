<?php
session_start();

include 'logged_in.php';

include 'permission_employee.php';

$user = $_SESSION['user'];

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Timer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles/dashboard.css">
    <script src="script/clock_in_out.js"></script>
    
</head>
<body>
<!-- navigation -->    
<?php include 'navigation.php'; ?>

<!-- dashboard -->    

<div class="timer-container">
        <div class="timer-display" id="currentDateDisplay">Date: --/--/----</div>
        <div class="timer-display" id="startTimeDisplay">Start Time: Not started yet</div>
        <div class="timer-display" id="elapsedTimeDisplay">Elapsed Time: 00:00:00</div>
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
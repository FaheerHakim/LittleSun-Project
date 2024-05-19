<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/Schedule.php"; // Update to use Schedule class
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/TimeOff.php"; // Include the TimeOff class

// Fetch existing users and locations from the database
$scheduleHandler = new Schedule();
$userHandler = new User();
$timeOffHandler = new TimeOff(); // Instantiate the TimeOff class

include 'logged_in.php';
include 'permission_manager.php';

// Get users and locations
$employeeUsers = $scheduleHandler->getEmployeeUsers();
$locations = $scheduleHandler->getLocations();

// Check if a user ID is provided in the URL parameter
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    
    // Find the user by ID
    $user = null;
    foreach ($employeeUsers as $empUser) {
        if ($empUser['user_id'] == $userId) {
            $user = $empUser;
            break;
        }
    }
    
    if ($user) {
        // Get assigned task types for the user
        $assignedTaskTypes = $userHandler->getAssignedTaskTypes($userId);
    }

}

// Get users and locations
$employeeUsers = $scheduleHandler->getEmployeeUsers();
$locations = $scheduleHandler->getLocations();

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Work Schedule</title>
    <link rel="stylesheet" href="styles/assign_task_types.css">
    <script src="script/assign_task_type.js" defer></script>
</head>
<body>
<div class="assign-schedule-container">
    <div class="assign-schedule-container">
    <a href="assign_employees.php" class="go-back-button" type="button">Go Back</a>
    <h1>Assign Work Schedule to <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h1>
    <?php if (empty($assignedTaskTypes)): ?>
        <p>No assigned task types found for this user.</p>
        <button onclick="location.href='schedule_manager.php'">Overview work schedule</button>
    <?php else: ?>
        <form action="assign_work_schedule.php" method="post" onsubmit="return confirmAssignment()">
            <label for="task_type_id">Assigned task types:</label>
            <select name="task_type_id" id="task_type_id">
                <?php foreach ($assignedTaskTypes as $taskType): ?>
                    <option value="<?php echo $taskType['task_type_id']; ?>"><?php echo $taskType['task_type_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="location_id">Location:</label>
            <select name="location_id" id="location_id">
                <?php foreach ($locations as $location): ?>
                    <option value="<?php echo $location['location_id']; ?>"><?php echo $location['city']; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required><br>
            <label for="start_time">Start Time:</label>
            <input type="time" id="start_time" name="start_time" required><br>
            <label for="end_time">End Time:</label>
            <input type="time" id="end_time" name="end_time" required><br>
            <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
            <button class="assign-button" type="submit">Assign Work Schedule</button>
        </form>
    <?php endif; ?>
</div>
<?php

?>
</body>
</html>

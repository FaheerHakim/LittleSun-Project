<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/Schedule.php"; // Update to use Schedule class

// Fetch existing users and locations from the database
$scheduleHandler = new Schedule();

include 'logged_in.php';

include 'permission_manager.php';

// Add task schedule for user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['location_id']) && isset($_POST['task_type_id']) && isset($_POST['start_time']) && isset($_POST['end_time']) && isset($_POST['date'])) {
    $userId = $_POST['user_id'];
    $locationId = $_POST['location_id'];
    $taskTypeId = $_POST['task_type_id'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];
    $date = $_POST['date'];
    
    // Assign task schedule to user
    $success = $scheduleHandler->assignTaskSchedule($userId, $locationId, $taskTypeId, $startTime, $endTime, $date);

    if ($success) {
        // Task schedule assigned successfully
        // You can redirect the user or display a success message
    } else {
        // There was an error assigning the task schedule
        // You can display an error message or handle the situation accordingly
    }
}

// Get users and task types
$employeeUsers = $scheduleHandler->getEmployeeUsers();
$taskTypes = $scheduleHandler->getTaskTypes();
$locations = $scheduleHandler->getLocations();

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Work Schedule</title>
    <link rel="stylesheet" href="styles/assign_work_schedule.css">
    <script src="script/assign_work_schedule.js" defer></script>
</head>
<body>
<div class="assign-schedule-container">
    <a href="dashboard.php" class="go-back-button" type="button">Go Back</a>
    <h1>Assign Work Schedule to Users</h1>
    <input type="text" id="searchBar" placeholder="Search for users..." onkeyup="searchUsers()">
    <div class="sub-container">
        <?php foreach ($employeeUsers as $user): ?>
            <div class="user-box">
                <p>User: <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>
                <form action="assign_work_schedule.php" method="post" onsubmit="return confirmAssignment()">
                    <label for="location_id">Location:</label>
                    <select name="location_id" id="location_id">
                        <?php foreach ($locations as $location): ?>
                            <option value="<?php echo $location['location_id']; ?>"><?php echo $location['city']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="task_type_id">Task Type:</label>
                    <select name="task_type_id" id="task_type_id">
                        <?php foreach ($taskTypes as $taskType): ?>
                            <option value="<?php echo $taskType['task_type_id']; ?>"><?php echo $taskType['task_type_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="start_time">Start Time:</label>
                    <input type="time" id="start_time" name="start_time" required><br>
                    <label for="end_time">End Time:</label>
                    <input type="time" id="end_time" name="end_time" required><br>
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required><br>
                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                    <button class="assign-button" type="submit">Assign Work Schedule</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>

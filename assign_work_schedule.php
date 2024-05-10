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

// Add task schedule for user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['location_id']) && isset($_POST['task_type_id']) && isset($_POST['start_time']) && isset($_POST['end_time']) && isset($_POST['date'])) {
    $userId = $_POST['user_id'];
    $locationId = $_POST['location_id'];
    $taskTypeId = $_POST['task_type_id'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];
    $date = $_POST['date'];
    
    // Check if the user has approved time off for the selected date
    if ($timeOffHandler->hasApprovedTimeOff($userId, $date)) {
        // Fetch time off details for the user
        $timeOffDetails = $timeOffHandler->getApprovedTimeOffDetails($userId, $date);
        
        // Check if time off details are not empty
        if (!empty($timeOffDetails)) {
            // Extract begin date and end date from the time off details
            $beginDate = $timeOffDetails['start_date'];
            $endDate = $timeOffDetails['end_date'];
            
            // Display error message with begin date and end date
            echo "<script>alert('This person has an approved time off from $beginDate to $endDate. Select another time slot');</script>";
        } else {
            // Define error message
            $errorMessage = "Time off details not found.";
        }
    }
    
    // Display form if no error message is set
    if (!isset($errorMessage)) {
        // Rest of your code for displaying the form
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
    <a href="work_schedule_manager.php" class="go-back-button" type="button">Go Back</a>
    <h1>Assign Work Schedule to Users</h1>
    <input type="text" id="searchBar" placeholder="Search for users..." onkeyup="searchUsers()">
    <div class="sub-container">
        <?php foreach ($employeeUsers as $user): ?>
            <div class="user-box">
                <p>User: <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>
                
                <?php
                // Get assigned task types for the user
                $assignedTaskTypes = $userHandler->getAssignedTaskTypes($user['user_id']);
                // Filter out assigned task types that already have a work schedule
                $availableTaskTypes = array_filter($assignedTaskTypes, function ($taskType) use ($user, $scheduleHandler) {
                    return !$scheduleHandler->hasWorkSchedule($user['user_id'], $taskType['task_type_id']);
                });
                // Display the form if there are available task types, otherwise display a message
                if (!empty($availableTaskTypes)): ?>
                    <?php if (isset($errorMessage)): ?>
                        <p><?php echo $errorMessage; ?></p>
                        <button onclick="location.href='overview_work_schedule.php'">Overview work schedule</button>
                    <?php else: ?>
                        <form action="assign_work_schedule.php" method="post" onsubmit="return confirmAssignment()">
                            <label for="task_type_id">Assigned task types:</label>
                            <select name="task_type_id" id="task_type_id">
                                <?php foreach ($availableTaskTypes as $taskType): ?>
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
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <button class="assign-button" type="submit">Assign Work Schedule</button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <p>All task types have been assigned a work schedule for this user.</p>
                    <button onclick="location.href='overview_work_schedule.php'">Overview work schedule</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>

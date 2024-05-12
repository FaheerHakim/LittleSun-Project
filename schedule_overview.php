<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Include necessary classes and files
require_once __DIR__ . "/classes/Schedule.php"; // Include the Schedule class
require_once __DIR__ . "/classes/User.php"; // Include the User class
require_once __DIR__ . "/classes/Location.php"; // Include the Location class
require_once __DIR__ . "/classes/TaskType.php"; // Include the TaskType class

// Check if the user is logged in and has the required permissions
include 'logged_in.php';
include 'permission_manager.php';

// Create instances of the required classes
$scheduleHandler = new Schedule();
$userHandler = new User();
$locationHandler = new Location();
$taskTypeHandler = new TaskType();

// Set the default date to today
$currentDate = date("Y-m-d");

// Check if a different date is selected
if (isset($_GET['date'])) {
    // Validate the date format
    $selectedDate = $_GET['date'];
    if (strtotime($selectedDate) !== false) {
        $currentDate = $selectedDate;
    } else {
        // Invalid date format, fallback to today
        $currentDate = date("Y-m-d");
    }
}

// Fetch work schedule for the selected date
$workSchedule = $scheduleHandler->getWorkScheduleForDate($currentDate);

// Determine the previous and next dates
$prevDate = date("Y-m-d", strtotime($currentDate . " -1 day"));
$nextDate = date("Y-m-d", strtotime($currentDate . " +1 day"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Overview Work Schedule</title>
    <link rel="stylesheet" href="styles/manage_time_off.css">
</head>
<body>
<div class="container">
<a href="work_schedule_manager.php" class="go-back" type="button">Go Back</a>

      <!-- Navigation to switch between views -->
      <div class="view-navigation">
        <a href="schedule_overview.php">Daily</a>
        <a href="weekly_schedule.php">Weekly</a>
        <a href="monthly_schedule.php">Monthly</a>
    </div>
    <h1>Work Schedule for <?php echo date("l, F j, Y", strtotime($currentDate)); ?></h1>

    <div class="navigation">
        <a href="?date=<?php echo $prevDate; ?>">Previous Day</a>
        <a href="?date=<?php echo $nextDate; ?>">Next Day</a>
    </div>

    <div class="section">
    <div class="work-schedule">
        <?php if (empty($workSchedule)): ?>
            <p>No work schedule available for this day.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>User</th>
                    <th>Task Type</th>
                    <th>Location</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
                <?php foreach ($workSchedule as $schedule): ?>
                    <tr>
                        <td><?php echo $userHandler->getUserNameById($schedule['user_id']); ?></td>
                        <td><?php echo $taskTypeHandler->getTaskTypeNameById($schedule['task_type_id'])['task_type_name']; ?></td>
                        <td><?php echo $locationHandler->getLocationNameById($schedule['location_id'])['city']; ?></td>
                        <td><?php echo $schedule['start_time']; ?></td>
                        <td><?php echo $schedule['end_time']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>
</div>
</body>
</html>
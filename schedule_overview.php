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
    <link rel="stylesheet" href="styles/overview_work_schedule.css">
</head>
<body>
<div class="container">
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
                <?php foreach ($workSchedule as $schedule): ?>
                    <div class="schedule-item">
                        <h3>User: <?php echo $userHandler->getUserNameById($schedule['user_id']); ?></h3>
                        <p>Task Type: <?php echo $taskTypeHandler->getTaskTypeNameById($schedule['task_type_id'])['task_type_name']; ?></p>
                        <p>Location: <?php echo $locationHandler->getLocationNameById($schedule['location_id'])['city']; ?></p>
                        <p>Start Time: <?php echo $schedule['start_time']; ?></p>
                        <p>End Time: <?php echo $schedule['end_time']; ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>

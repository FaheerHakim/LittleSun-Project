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

// Create an instance of the Schedule class
$scheduleHandler = new Schedule();
$userHandler = new User();
$locationHandler = new Location();
$taskTypeHandler = new TaskType();

// Get today's date
$currentDate = date("Y-m-d");

// Fetch active work schedule for today
$workScheduleToday = $scheduleHandler->getWorkScheduleForDate($currentDate);

// Fetch active work schedule for this week
$startDateOfWeek = date("Y-m-d", strtotime('monday this week'));
$endDateOfWeek = date("Y-m-d", strtotime('sunday this week'));
$workScheduleThisWeek = $scheduleHandler->getWorkScheduleForPeriod($startDateOfWeek, $endDateOfWeek);

// Fetch active work schedule for this month
$startDateOfMonth = date("Y-m-01");
$endDateOfMonth = date("Y-m-t");
$workScheduleThisMonth = $scheduleHandler->getWorkScheduleForPeriod($startDateOfMonth, $endDateOfMonth);
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
    <h1>Overview Work Schedule</h1>

    <div class="section">
        <h2>Work Schedule for Today</h2>
        <div class="work-schedule">
            <?php foreach ($workScheduleToday as $schedule): ?>
                <div class="schedule-item">
                    <h3>User: <?php echo $userHandler->getUserNameById($schedule['user_id']); ?></p></h3>
                    <p>Task Type: <?php echo $taskTypeHandler->getTaskTypeNameById($schedule['task_type_id'])['task_type_name']; ?></p>
                    <p>Location: <?php echo $locationHandler->getLocationNameById($schedule['location_id'])['city']; ?></p>
                    <p>Start Time: <?php echo $schedule['start_time']; ?></p>
                    <p>End Time: <?php echo $schedule['end_time']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="section">
        <h2>Work Schedule for This Week</h2>
        <div class="work-schedule">
            <?php foreach ($workScheduleThisWeek as $schedule): ?>
                <div class="schedule-item">
                    <p>User: <?php echo $userHandler->getUserNameById($schedule['user_id']); ?></p>
                    <p>Task Type: <?php echo $taskTypeHandler->getTaskTypeNameById($schedule['task_type_id'])['task_type_name']; ?></p>
                    <p>Location: <?php echo $locationHandler->getLocationNameById($schedule['location_id'])['city']; ?></p>
                    <p>Start Time: <?php echo $schedule['start_time']; ?></p>
                    <p>End Time: <?php echo $schedule['end_time']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="section">
        <h2>Work Schedule for This Month</h2>
        <div class="work-schedule">
            <?php foreach ($workScheduleThisMonth as $schedule): ?>
                <div class="schedule-item">
                    <p>User: <?php echo $userHandler->getUserNameById($schedule['user_id']); ?></p>
                    <p>Task Type: <?php echo $taskTypeHandler->getTaskTypeNameById($schedule['task_type_id'])['task_type_name']; ?></p>
                    <p>Location: <?php echo $locationHandler->getLocationNameById($schedule['location_id'])['city']; ?></p>
                    <p>Start Time: <?php echo $schedule['start_time']; ?></p>
                    <p>End Time: <?php echo $schedule['end_time']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>

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

// Set the default start and end dates for the current month
$currentDate = date("Y-m-d");
$startDateOfMonth = date("Y-m-01", strtotime($currentDate));
$endDateOfMonth = date("Y-m-t", strtotime($currentDate));

// Check if a different month is selected
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    // Validate the date formats
    $startDateOfMonth = $_GET['start_date'];
    $endDateOfMonth = $_GET['end_date'];
    if (strtotime($startDateOfMonth) !== false && strtotime($endDateOfMonth) !== false) {
        // Validate that the selected dates belong to the same month
        if (date("m", strtotime($startDateOfMonth)) === date("m", strtotime($endDateOfMonth))) {
            $currentDate = $startDateOfMonth;
        } else {
            // Invalid month selection, fallback to the current month
            $startDateOfMonth = date("Y-m-01", strtotime($currentDate));
            $endDateOfMonth = date("Y-m-t", strtotime($currentDate));
        }
    } else {
        // Invalid date formats, fallback to the current month
        $startDateOfMonth = date("Y-m-01", strtotime($currentDate));
        $endDateOfMonth = date("Y-m-t", strtotime($currentDate));
    }
}

// Fetch work schedule for the selected month
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
<a href="work_schedule_manager.php" class="go-back-button" type="button">Go Back</a>

<div class="container">
    <div class="view-navigation">
        <a href="schedule_overview.php">Daily</a>
        <a href="weekly_schedule.php">Weekly</a>
        <a href="monthly_schedule.php">Monthly</a>
    </div>
    <h1>Work Schedule for <?php echo date("F Y", strtotime($startDateOfMonth)); ?></h1>

    <div class="navigation">
    <a href="?start_date=<?php echo date("Y-m-01", strtotime($startDateOfMonth . " -1 month")); ?>&end_date=<?php echo date("Y-m-t", strtotime($startDateOfMonth . " -1 day")); ?>">Previous Month</a>
    <a href="?start_date=<?php echo date("Y-m-01", strtotime("first day of next month", strtotime($startDateOfMonth))); ?>&end_date=<?php echo date("Y-m-t", strtotime("last day of next month", strtotime($startDateOfMonth))); ?>">Next Month</a>
</div>

    <div class="section">
        <div class="work-schedule">
            <?php if (empty($workScheduleThisMonth)): ?>
                <p>No work schedule available for this month.</p>
            <?php else: ?>
                <?php foreach ($workScheduleThisMonth as $schedule): ?>
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

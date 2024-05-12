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

// Set the default start and end dates for the current week
$currentDate = date("Y-m-d");
$startDateOfWeek = date("Y-m-d", strtotime('monday this week', strtotime($currentDate)));
$endDateOfWeek = date("Y-m-d", strtotime('sunday this week', strtotime($currentDate)));

// Check if a different week is selected
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    // Validate the date formats
    $startDateOfWeek = $_GET['start_date'];
    $endDateOfWeek = $_GET['end_date'];
    if (strtotime($startDateOfWeek) !== false && strtotime($endDateOfWeek) !== false) {
        // Validate that the selected dates belong to the same week
        if (date("W", strtotime($startDateOfWeek)) === date("W", strtotime($endDateOfWeek))) {
            $currentDate = $startDateOfWeek;
        } else {
            // Invalid week selection, fallback to the current week
            $startDateOfWeek = date("Y-m-d", strtotime('monday this week', strtotime($currentDate)));
            $endDateOfWeek = date("Y-m-d", strtotime('sunday this week', strtotime($currentDate)));
        }
    } else {
        // Invalid date formats, fallback to the current week
        $startDateOfWeek = date("Y-m-d", strtotime('monday this week', strtotime($currentDate)));
        $endDateOfWeek = date("Y-m-d", strtotime('sunday this week', strtotime($currentDate)));
    }
}

// Fetch work schedule for the selected week
$workScheduleThisWeek = $scheduleHandler->getWorkScheduleForPeriod($startDateOfWeek, $endDateOfWeek);
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

    <div class="view-navigation">
    <a href="schedule_overview.php">Daily</a>
        <a href="weekly_schedule.php">Weekly</a>
        <a href="monthly_schedule.php">Monthly</a>
    </div>
<h1>Work Schedule for Week <?php echo date("M d", strtotime($startDateOfWeek)); ?> - <?php echo date("M d, Y", strtotime($endDateOfWeek)); ?></h1>

    <div class="navigation">
            <a href="?start_date=<?php echo date("Y-m-d", strtotime($startDateOfWeek . " -1 week")); ?>&end_date=<?php echo date("Y-m-d", strtotime($endDateOfWeek . " -1 week")); ?>">Previous Week</a>
            <a href="?start_date=<?php echo date("Y-m-d", strtotime($startDateOfWeek . " +1 week")); ?>&end_date=<?php echo date("Y-m-d", strtotime($endDateOfWeek . " +1 week")); ?>">Next Week</a>
    </div>

    <div class="section">
    <div class="work-schedule">
        <?php if (empty($workScheduleThisWeek)): ?>
            <p>No work schedule available for this week.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>User</th>
                    <th>Task Type</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
                <?php foreach ($workScheduleThisWeek as $schedule): ?>
                    <tr>
                        <td><?php echo $userHandler->getUserNameById($schedule['user_id']); ?></td>
                        <td><?php echo $taskTypeHandler->getTaskTypeNameById($schedule['task_type_id'])['task_type_name']; ?></td>
                        <td><?php echo $locationHandler->getLocationNameById($schedule['location_id'])['city']; ?></td>
                        <td><?php echo date("Y-m-d", strtotime($schedule['date'])); ?></td>
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

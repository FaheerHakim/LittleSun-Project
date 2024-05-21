<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'logged_in.php';

$user = $_SESSION['user'];
require_once __DIR__ . "/classes/TimeOff.php";
require_once __DIR__ . "/classes/Schedule.php";
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/TaskType.php";
require_once __DIR__ . "/classes/Location.php";

date_default_timezone_set('Europe/Brussels'); 

$timeOffHandler = new TimeOff();
$scheduleHandler = new Schedule();
$userHandler = new User();
$taskTypeHandler = new TaskType();
$locationHandler = new Location();

$pendingRequestsCount = $timeOffHandler->getPendingRequestsCount(); // This function will count the pending requests

$currentDate = date("Y-m-d");
$todaysSchedule = $scheduleHandler->getWorkScheduleForDate($currentDate);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles/dashboard.css">
    
</head>
<body>

<?php include 'navigation.php'; ?>

<div class="main">
    <h1><span class="title"> Dashboard </span></h1>
    
    <?php if(isset($user['type_user']) && $user['type_user'] != 'manager' && $user['type_user'] != 'employee'): ?>  
        <h2>Quick actions</h2>
        <div onclick="location.href='add_location.php';" class="info-square">Add & edit locations</div>
        <div onclick="location.href='add_manager.php';" class="info-square">Add manager</div>
        <div onclick="location.href='add_task_types.php';" class="info-square">Add & edit task types</div>
    <?php endif; ?>
  
     <?php if(isset($user['type_user']) && $user['type_user'] != 'admin' && $user['type_user'] != 'employee'): ?>
        <h2>Quick actions</h2>
         <div onclick="location.href='add_employee.php';" class="info-square">Add Employee</div>
        <div onclick="location.href='assign_task.php';" class="info-square">Assign task types</div>
        <div onclick="location.href='schedule_manager.php';" class="info-square">Overview work schedule</div>
        <h2>Pending time-off requests: <?php echo $pendingRequestsCount; ?></h2>
        <div onclick="location.href='manage_time_off.php';" class="info-square">Manage time off</div>
        <h2>Today's work schedule</h2>
        <div id="todays-schedule">
        <?php if (!empty($todaysSchedule)): ?>
                <?php foreach ($todaysSchedule as $schedule): ?>
                    <?php
                        $userName = $userHandler->getUserNameById($schedule['user_id']);
                        $taskTypeDetails = $taskTypeHandler->getTaskTypeNameById($schedule['task_type_id']);
                        $locationDetails = $locationHandler->getLocationNameById($schedule['location_id']);

                        $taskType = is_array($taskTypeDetails) && isset($taskTypeDetails['task_type_name']) ? $taskTypeDetails['task_type_name'] : 'Unknown task type';
                        $location = is_array($locationDetails) && isset($locationDetails['city']) ? $locationDetails['city'] : 'Unknown location';
                    ?>
                    <div class="schedule-entry">
                        <strong><?php echo htmlspecialchars($userName); ?></strong><br>
                        <?php echo htmlspecialchars($taskType); ?><br>
                        <?php echo htmlspecialchars($location); ?><br>
                        <?php echo htmlspecialchars($schedule['start_time'] ?? 'Unknown start time'); ?> - <?php echo htmlspecialchars($schedule['end_time'] ?? 'Unknown end time'); ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No work scheduled for today.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if(isset($user['type_user']) && $user['type_user'] != 'admin' && $user['type_user'] != 'manager'): ?>
        <h2>Quick actions</h2>
        <div onclick="location.href='clock_in_out.php';" class="info-square">Clock in & out</div>
        <div onclick="location.href='request_time_off.php';" class="info-square">Request time off</div>
         <div onclick="location.href='schedule_employee.php';" class="info-square">Work schedule</div>
        <h2>Today's work schedule</h2>

    <?php endif; ?>
    
</div>
</body>
</html>

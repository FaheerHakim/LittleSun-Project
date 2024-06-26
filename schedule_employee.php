<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . "/classes/Schedule.php";
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/Location.php";
require_once __DIR__ . "/classes/TaskType.php";
require_once __DIR__ . "/classes/TimeOff.php";

date_default_timezone_set('Europe/Brussels'); 

include 'logged_in.php';
include 'permission_employee.php';

$user = $_SESSION['user'];
$userId = $user['user_id'];

$scheduleHandler = new Schedule();
$userHandler = new User();
$locationHandler = new Location();
$taskTypeHandler = new TaskType();
$timeOffHandler = new TimeOff();

$viewType = isset($_GET['view']) ? $_GET['view'] : 'daily';
$currentDate = date("Y-m-d");
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : $currentDate;
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : $currentDate;

$workSchedule = [];

switch ($viewType) {
    case 'daily':
        $workSchedule = $scheduleHandler->getWorkScheduleForPeriod($startDate, $endDate, $userId);
        break;
    case 'weekly':
        $workSchedule = $scheduleHandler->getWorkScheduleForPeriod($startDate, $endDate, $userId);
        break;
    case 'monthly':
    default:
        $workSchedule = $scheduleHandler->getWorkScheduleForPeriod($startDate, $endDate, $userId);
        break;
}

$scheduleByDate = [];

if ($workSchedule !== null) {
    foreach ($workSchedule as $schedule) {
        $date = date("Y-m-d", strtotime($schedule['date']));
        $scheduleByDate[$date][] = [
            'task_type' => $taskTypeHandler->getTaskTypeNameById($schedule['task_type_id'])['task_type_name'],
            'location' => $locationHandler->getLocationById($schedule['location_id'])['city'],
            'start_time' => date("H:i", strtotime($schedule['start_time'])), 
            'end_time' => date("H:i", strtotime($schedule['end_time'])) 
        ];
    }
}

$timeOffEvents = $timeOffHandler->getApprovedTimeOffRequestsForUser($userId, $startDate, $endDate);

$timeOffByDate = [];
foreach ($timeOffEvents as $timeOff) {
    $timeOffStartDate = date("Y-m-d", strtotime($timeOff['start_date']));
    $timeOffEndDate = date("Y-m-d", strtotime($timeOff['end_date']));

    for ($date = $timeOffStartDate; $date <= $timeOffEndDate; $date = date('Y-m-d', strtotime($date . ' +1 day'))) {
        if ($date >= $startDate && $date <= $endDate) {
            $timeOffByDate[$date][] = [
                'reason' => $timeOff['reason']
            ];
        }
    }
}

function getPreviousPeriod($viewType, $startDate)
{
    switch ($viewType) {
        case 'daily':
            return date("Y-m-d", strtotime($startDate . " -1 day"));
        case 'weekly':
            return date("Y-m-d", strtotime($startDate . " -1 week"));
        case 'monthly':
            return date("Y-m-01", strtotime($startDate . " -1 month"));
    }
}

function getNextPeriod($viewType, $startDate)
{
    switch ($viewType) {
        case 'daily':
            return date("Y-m-d", strtotime($startDate . " +1 day"));
        case 'weekly':
            return date("Y-m-d", strtotime($startDate . " +1 week"));
        case 'monthly':
            return date("Y-m-01", strtotime($startDate . " +1 month"));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Work Schedule</title>
    <link rel="stylesheet" href="styles/schedule_manager.css">
</head>
<body>
<div class="container">
    <a href="dashboard.php" class="go-back-button" type="button">Go Back</a>

    <h1>Work Schedule for <?php echo date("F Y", strtotime($startDate)); ?></h1>

    <div class="form-container">
        <div class="form-content">
            <div class="view-navigation">
                <a href="?view=daily&start_date=<?php echo $currentDate; ?>&end_date=<?php echo $currentDate; ?>">Daily</a>
                <a href="?view=weekly&start_date=<?php echo date("Y-m-d", strtotime('monday this week', strtotime($currentDate))); ?>&end_date=<?php echo date("Y-m-d", strtotime('sunday this week', strtotime($currentDate))); ?>">Weekly</a>
                <a href="?view=monthly&start_date=<?php echo date("Y-m-01", strtotime($currentDate)); ?>&end_date=<?php echo date("Y-m-t", strtotime($currentDate)); ?>">Monthly</a>
            </div>
    
            <div class="navigation">
                <a href="?view=<?php echo $viewType; ?>&start_date=<?php echo getPreviousPeriod($viewType, $startDate); ?>&end_date=<?php echo getPreviousPeriod($viewType, $endDate); ?>">Previous</a>
                <a href="?view=<?php echo $viewType; ?>&start_date=<?php echo getNextPeriod($viewType, $startDate); ?>&end_date=<?php echo getNextPeriod($viewType, $endDate); ?>">Next</a>
            </div>

            <?php if ($viewType == 'daily'): ?>
                <div class="day">
                    <div class="day-header"><?php echo date("F j, l", strtotime($startDate)); ?></div>
                    <?php 
                    $date = date("Y-m-d", strtotime($startDate));
                    $hasEvents = false;
                    ?>
                    <?php if (isset($scheduleByDate[$date])): ?>
                        <?php 
                        $hasEvents = true;
                        foreach ($scheduleByDate[$date] as $event): ?>
                            <div class="event">
                                <strong><?php echo isset($event['user']) ? $event['user'] : ''; ?></strong><br>
                                <?php echo isset($event['task_type']) ? $event['task_type'] : ''; ?><br>
                                <?php echo $event['location'] . '<br>';?>
                                <?php echo isset($event['start_time']) && isset($event['end_time']) ? $event['start_time'] . ' - ' . $event['end_time'] : ''; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if (isset($timeOffByDate[$date])): ?>
                        <?php 
                        $hasEvents = true;
                        foreach ($timeOffByDate[$date] as $timeOffEvent): ?>
                            <div class="event time-off">
                                <strong>Time Off</strong><br>
                                <?php echo htmlspecialchars($timeOffEvent['reason']); ?><br>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if (!$hasEvents): ?>
                        <div class="no-events">No events scheduled for today.</div>
                    <?php endif; ?>
                </div>

            <?php elseif ($viewType == 'weekly'): ?>
                <div class="calendar">
                    <?php for ($i = 0; $i < 7; $i++): ?>
                        <div class="day">
                            <div class="day-header"><?php echo date("l, F j", strtotime("$startDate +$i days")); ?></div>
                            <?php 
                            $date = date("Y-m-d", strtotime("$startDate +$i days"));
                            $hasEvents = false;
                            ?>
                            <?php if (isset($scheduleByDate[$date])): ?>
                                <?php 
                                $hasEvents = true;
                                foreach ($scheduleByDate[$date] as $event): ?>
                                    <div class="event">
                                        <strong><?php echo isset($event['user']) ? $event['user'] : ''; ?></strong><br>
                                        <?php echo isset($event['task_type']) ? $event['task_type'] : ''; ?><br>
                                        <?php echo $event['location'] . '<br>';?>
                                        <?php echo isset($event['start_time']) && isset($event['end_time']) ? $event['start_time'] . ' - ' . $event['end_time'] : ''; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if (isset($timeOffByDate[$date])): ?>
                                <?php 
                                $hasEvents = true;
                                foreach ($timeOffByDate[$date] as $timeOffEvent): ?>
                                    <div class="event time-off">
                                        <strong>Time Off</strong><br>
                                        <?php echo htmlspecialchars($timeOffEvent['reason']); ?><br>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if (!$hasEvents): ?>
                                <div class="no-events">No events scheduled for this day.</div>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>

            <?php else: ?>
                <div class="calendar">
                    <div class="header">Mon</div>
                    <div class="header">Tue</div>
                    <div class="header">Wed</div>
                    <div class="header">Thu</div>
                    <div class="header">Fri</div>
                    <div class="header">Sat</div>
                    <div class="header">Sun</div>

                    <?php
                    $firstDayOfWeek = date("N", strtotime($startDate));
                    for ($i = 1; $i < $firstDayOfWeek; $i++) {
                        echo '<div class="day empty"></div>';
                    }

                    $daysInMonth = date("t", strtotime($startDate));
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $date = date("Y-m-d", strtotime($startDate . " +".($day-1)." days"));
                        echo '<div class="day">';
                        echo '<strong>' . $day . '</strong>';

                        if (isset($scheduleByDate[$date])) {
                            foreach ($scheduleByDate[$date] as $event) {
                                echo '<div class="event">';
                                echo $event['task_type'] . '<br>';
                                echo $event['location'] . '<br>';
                                echo $event['start_time'] . ' - ' . $event['end_time'];
                                echo '</div>';
                            }
                        }
                        if (isset($timeOffByDate[$date])) {
                            foreach ($timeOffByDate[$date] as $timeOffEvent) {
                                echo '<div class="event time-off">';
                                echo '<strong>Time Off</strong><br>';
                                echo htmlspecialchars($timeOffEvent['reason']) . '<br>';
                                echo '</div>';
                            }
                        }
                        echo '</div>';
                    }

                    $lastDayOfWeek = date("N", strtotime($endDate));
                    for ($i = $lastDayOfWeek; $i < 7; $i++) {
                        echo '<div class="day empty"></div>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>

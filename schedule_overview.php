<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Include necessary classes and files
require_once __DIR__ . "/classes/Schedule.php"; // Include the Schedule class
require_once __DIR__ . "/classes/User.php"; // Include the User class
require_once __DIR__ . "/classes/Location.php"; // Include the Location class
require_once __DIR__ . "/classes/TaskType.php"; // Include the TaskType class
require_once __DIR__ . "/classes/TimeOff.php";
// Check if the user is logged in and has the required permissions
include 'logged_in.php';
include 'permission_manager.php';

// Create instances of the required classes
$scheduleHandler = new Schedule();
$userHandler = new User();
$locationHandler = new Location();
$taskTypeHandler = new TaskType();
$timeOffHandler = new TimeOff(); // Instantiate the TimeOff class 

// Determine the view type (daily, weekly, monthly)
$viewType = isset($_GET['view']) ? $_GET['view'] : 'monthly';
$currentDate = date("Y-m-d");

// Set default dates based on the view type
switch ($viewType) {
    case 'daily':
        $startDate = $currentDate;
        $endDate = $currentDate;
        break;
    case 'weekly':
        $startDate = date("Y-m-d", strtotime('monday this week', strtotime($currentDate)));
        $endDate = date("Y-m-d", strtotime('sunday this week', strtotime($currentDate)));
        break;
    case 'monthly':
    default:
        $startDate = date("Y-m-01", strtotime($currentDate));
        $endDate = date("Y-m-t", strtotime($currentDate));
        break;
}

// Check if a different period is selected
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    // Validate the date formats
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];
    if (strtotime($startDate) === false || strtotime($endDate) === false) {
        // Invalid date formats, fallback to the default period
        $startDate = $currentDate;
        $endDate = $currentDate;
    }
}

// Fetch work schedule for the selected period
$workSchedule = $scheduleHandler->getWorkScheduleForPeriod($startDate, $endDate);



// Create an associative array to store schedule data by date
$scheduleByDate = [];
foreach ($workSchedule as $schedule) {
    $date = date("Y-m-d", strtotime($schedule['date']));
    if (!isset($scheduleByDate[$date])) {
        $scheduleByDate[$date] = [];
    }
  if ($timeOffHandler->hasApprovedTimeOff($schedule['user_id'], $date)) {
        // Get the approved time off details
        $timeOffDetails = $timeOffHandler->getApprovedTimeOffDetails($schedule['user_id'], $date);
        
        // Display the approved time off details instead of regular work schedule events
        $timeOffStartDate = $timeOffDetails['start_date'];
        $timeOffEndDate = $timeOffDetails['end_date'];
        $currentOffDate = $timeOffStartDate;

        while (strtotime($currentOffDate) <= strtotime($timeOffEndDate)) {
            if (strtotime($currentOffDate) >= strtotime($startDate) && strtotime($currentOffDate) <= strtotime($endDate)) {
                if (!isset($scheduleByDate[$currentOffDate])) {
                    $scheduleByDate[$currentOffDate] = [];
                }
                $scheduleByDate[$currentOffDate][] = [
                    'user' => $userHandler->getUserNameById($schedule['user_id']),
                    'task_type' => 'Time Off', // Assuming 'Time Off' is a task type
                    'location' => 'Off', // Assuming 'Off' is the location for time off
                    'start_time' => 'Full Day', // Indicate the whole day is off
                    'end_time' => 'Full Day',
                    'time_off_reason' => $timeOffDetails['reason'], // Display the reason for time off
                    'time_off_additional_notes' => $timeOffDetails['additional_notes'] // Display additional notes for time off
                ];
            }
            $currentOffDate = date("Y-m-d", strtotime($currentOffDate . ' +1 day'));
        }
    } else {
        // Add the regular work schedule entry if there is no approved time off
        $scheduleByDate[$date][] = [
            'user' => $userHandler->getUserNameById($schedule['user_id']),
            'task_type' => $taskTypeHandler->getTaskTypeNameById($schedule['task_type_id'])['task_type_name'],
            'location' => $locationHandler->getLocationNameById($schedule['location_id'])['city'],
            'start_time' => $schedule['start_time'],
            'end_time' => $schedule['end_time']
        ];
    }
}



// Helper functions for navigation links
function getPreviousPeriod($viewType, $startDate) {
    switch ($viewType) {
        case 'daily':
            return date("Y-m-d", strtotime($startDate . " -1 day"));
        case 'weekly':
            return date("Y-m-d", strtotime($startDate . " -1 week"));
        case 'monthly':
            return date("Y-m-01", strtotime($startDate . " -1 month"));
    }
}

function getNextPeriod($viewType, $startDate) {
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
    <title>Overview Work Schedule</title>
    <link rel="stylesheet" href="styles/manage_time_off.css">
    <style>
        .calendar, .week, .day {
            display: grid;
            gap: 1px;
            background-color: #ddd;
        }
        .calendar {
            grid-template-columns: repeat(7, 1fr);
        }
        .week {
            grid-template-columns: repeat(7, 1fr);
        }
        .day {
            grid-template-columns: 1fr;
        }
        .calendar .day, .week .day, .day .event {
            background-color: #fff;
            padding: 10px;
            border: 1px solid #ddd;
            min-height: 100px;
        }
        .calendar .header, .week .header {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            padding: 10px;
        }
        .calendar .event, .week .event, .day .event {
            margin: 5px 0;
            padding: 5px;
            background-color: #e0f7fa;
            border-left: 4px solid #00796b;
        }
        .day-header {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="work_schedule_manager.php" class="go-back" type="button">Go Back</a>
    <div class="view-navigation">
        <a href="?view=daily&start_date=<?php echo $currentDate; ?>&end_date=<?php echo $currentDate; ?>">Daily</a>
        <a href="?view=weekly&start_date=<?php echo date("Y-m-d", strtotime('monday this week', strtotime($currentDate))); ?>&end_date=<?php echo date("Y-m-d", strtotime('sunday this week', strtotime($currentDate))); ?>">Weekly</a>
        <a href="?view=monthly&start_date=<?php echo date("Y-m-01", strtotime($currentDate)); ?>&end_date=<?php echo date("Y-m-t", strtotime($currentDate)); ?>">Monthly</a>
    </div>
    <h1>Work Schedule for <?php echo date("F Y", strtotime($startDate)); ?></h1>

    <div class="navigation">
        <a href="?view=<?php echo $viewType; ?>&start_date=<?php echo getPreviousPeriod($viewType, $startDate); ?>&end_date=<?php echo getPreviousPeriod($viewType, $endDate); ?>">Previous</a>
        <a href="?view=<?php echo $viewType; ?>&start_date=<?php echo getNextPeriod($viewType, $startDate); ?>&end_date=<?php echo getNextPeriod($viewType, $endDate); ?>">Next</a>
    </div>

    <?php if ($viewType == 'daily'): ?>
        <div class="day">
            <div class="day-header"><?php echo date("l, F j, Y", strtotime($startDate)); ?></div>
            <?php if (isset($scheduleByDate[$startDate])): ?>
                <?php foreach ($scheduleByDate[$startDate] as $event): ?>
                    <div class="event">
                        <strong><?php echo $event['user']; ?></strong><br>
                        <?php echo $event['task_type']; ?><br>
                        <?php echo $event['location']; ?><br>
                        <?php echo $event['start_time']; ?> - <?php echo $event['end_time']; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No work schedule available for this day.</p>
            <?php endif; ?>
        </div>
    <?php elseif ($viewType == 'weekly'): ?>
        <div class="week">
            <?php for ($i = 0; $i < 7; $i++): ?>
                <?php
                $date = date("Y-m-d", strtotime($startDate . " +$i days"));
                $dayName = date("l", strtotime($date));
                ?>
                <div class="day">
                    <div class="header"><?php echo $dayName; ?></div>
                    <?php if (isset($scheduleByDate[$date])): ?>
                        <?php foreach ($scheduleByDate[$date] as $event): ?>
                            <div class="event">
                                <strong><?php echo $event['user']; ?></strong><br>
                                <?php echo $event['task_type']; ?><br>
                                <?php echo $event['location']; ?><br>
                                <?php echo $event['start_time']; ?> - <?php echo $event['end_time']; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No work schedule available for this day.</p>
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
            // Fill the first row with empty cells if the month doesn't start on Monday
            $firstDayOfWeek = date("N", strtotime($startDate));
            for ($i = 1; $i < $firstDayOfWeek; $i++) {
                echo '<div class="day empty"></div>';
            }

            // Fill the calendar with the days of the month
            $daysInMonth = date("t", strtotime($startDate));
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = date("Y-m-d", strtotime($startDate . " +".($day-1)." days"));
                echo '<div class="day">';
                echo '<strong>' . $day . '</strong>';

                if (isset($scheduleByDate[$date])) {
                    foreach ($scheduleByDate[$date] as $event) {
                        echo '<div class="event">';
                        echo '<strong>' . $event['user'] . '</strong><br>';
                        echo $event['task_type'] . '<br>';
                        echo $event['location'] . '<br>';
                        echo $event['start_time'] . ' - ' . $event['end_time'];
                        echo '</div>';
                    }
                }
                echo '</div>';
            }

            // Fill the last row with empty cells if the month doesn't end on Sunday
            $lastDayOfWeek = date("N", strtotime($endDate));
            for ($i = $lastDayOfWeek; $i < 7; $i++) {
                echo '<div class="day empty"></div>';
            }
            ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

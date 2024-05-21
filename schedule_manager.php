<?php
// Start session and include necessary files
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . "/classes/Schedule.php";
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/Location.php";
require_once __DIR__ . "/classes/TaskType.php";
require_once __DIR__ . "/classes/TimeOff.php";

// Check if the user is logged in and has the required permissions
include 'logged_in.php';
include 'permission_manager.php';

$scheduleHandler = new Schedule();
$userHandler = new User();
$locationHandler = new Location();
$taskTypeHandler = new TaskType();
$timeOffHandler = new TimeOff();

$viewType = isset($_GET['view']) ? $_GET['view'] : 'monthly';
$currentDate = date("Y-m-d");
$startDate = $endDate = null;

// Set default dates based on the view type
switch ($viewType) {
    case 'daily':
        $startDate = $endDate = $currentDate;
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
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];
    if (strtotime($startDate) === false || strtotime($endDate) === false) {
        // Invalid date formats, fallback to the default period
        $startDate = $endDate = $currentDate;
    }
}

$allLocations = $locationHandler->getAllLocations();

// Initialize an associative array to store schedule data by location
$scheduleByLocation = array_fill_keys(array_column($allLocations, 'location_id'), []);
$selectedLocation = isset($_GET['location']) ? $_GET['location'] : 'all';

// Fetch work schedule for the selected period
$workScheduleByLocation = [];
foreach ($allLocations as $location) {
    $locationId = $location['location_id'];
    $workScheduleByLocation[$locationId] = $scheduleHandler->getWorkScheduleForLocation($startDate, $endDate, $locationId);
    foreach ($workScheduleByLocation[$locationId] as $schedule) {
        $date = date("Y-m-d", strtotime($schedule['date']));
        $scheduleByLocation[$locationId][$date][] = [
            'user_id' => $schedule['user_id'],
            'user' => $userHandler->getUserNameById($schedule['user_id']),
            'task_type' => $taskTypeHandler->getTaskTypeNameById($schedule['task_type_id'])['task_type_name'],
            'start_time' => date("H:i", strtotime($schedule['start_time'])), // Format start_time
            'end_time' => date("H:i", strtotime($schedule['end_time'])) // Format end_time
        ];
    }
}

$timeOffEvents = $timeOffHandler->getApprovedTimeOffRequests($startDate, $endDate);

// Initialize an array to store time off events by date
$timeOffByDate = [];
foreach ($timeOffEvents as $timeOff) {
    $timeOffStartDate = date("Y-m-d", strtotime($timeOff['start_date']));
    $timeOffEndDate = date("Y-m-d", strtotime($timeOff['end_date']));
    
    // Loop through each date between start and end date of time-off request
    for ($date = $timeOffStartDate; $date <= $timeOffEndDate; $date = date('Y-m-d', strtotime($date . ' +1 day'))) {
        $timeOffByDate[$date][] = [
            'user_id' => $timeOff['user_id'],
            'user' => $userHandler->getUserNameById($timeOff['user_id']),
            'reason' => $timeOff['reason']
        ];

        // Check if the user was scheduled on this day
        $scheduledWorkDay = $scheduleHandler->getWorkScheduleForUserAndDate($timeOff['user_id'], $date);
        if ($scheduledWorkDay) {
            // If scheduled, add work schedule details to time-off event
            $timeOffByDate[$date][0]['task_type'] = $taskTypeHandler->getTaskTypeNameById($scheduledWorkDay['task_type_id'])['task_type_name'];
            $timeOffByDate[$date][0]['location'] = $locationHandler->getLocationNameById($scheduledWorkDay['location_id']);
            $timeOffByDate[$date][0]['start_time'] = $scheduledWorkDay['start_time'];
            $timeOffByDate[$date][0]['end_time'] = $scheduledWorkDay['end_time'];
        }
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
    <link rel="stylesheet" href="styles/schedule_manager.css">
    <script src="script/schedule_manager.js" defer ></script>
</head>
<body>
   

<div class="container">

    <a href="work_schedule_manager.php" class="go-back-button" type="button">Go Back</a>
    <a href="assign_employees.php" class="go-back-button" type="button">Assign Work </a>


    <div class="location-dropdown">
        <label for="location">Select Location:</label>
        <select id="location" name="location">
            <option value="all">All Locations</option>
            <?php foreach ($allLocations as $location): ?>
                <option value="<?php echo $location['location_id']; ?>"><?php echo $location['city']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
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
            <div class="day-header"><?php echo date("l, F j, Y", strtotime($startDate)); ?></div>
            <?php 
            $date = date("Y-m-d", strtotime($startDate));
            $hasEvents = false;
            foreach ($allLocations as $location): 
                $locationId = $location['location_id'];
                $locationName = $location['city'];
            ?>
                <?php if (isset($scheduleByLocation[$locationId][$date])): ?>
                    <?php 
                    $hasEvents = true;
                    foreach ($scheduleByLocation[$locationId][$date] as $event): ?>
                        <div class="event" data-location-id="<?php echo $locationId; ?>" data-user-id="<?php echo $event['user_id']; ?>">
                            <strong><?php echo $event['user']; ?></strong><br>
                            <?php echo $event['task_type']; ?><br>
                            <?php echo $locationName; ?><br>
                            <?php echo $event['start_time']; ?> - <?php echo $event['end_time']; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if (!$hasEvents): ?>
                <div class="no-events">No events scheduled for today.</div>
            <?php endif; ?>
        </div>
    <?php elseif ($viewType == 'weekly'): ?>
            <div class="calendar">
                 <?php for ($i = 0; $i < 7; $i++): ?>
                <div class="day">
                <div class="day-header"><?php echo date("l, F j, Y", strtotime("$startDate +$i days")); ?></div>
                <?php 
                $date = date("Y-m-d", strtotime("$startDate +$i days"));
                $hasEvents = false;
                foreach ($allLocations as $location): 
                    $locationId = $location['location_id'];
                    $locationName = $location['city'];
                ?>
                    <?php if (isset($scheduleByLocation[$locationId][$date])): ?>
                        <?php 
                        $hasEvents = true;
                        foreach ($scheduleByLocation[$locationId][$date] as $event): ?>
                            <div class="event" data-location-id="<?php echo $locationId; ?>" data-user-id="<?php echo $event['user_id']; ?>">
                                <strong><?php echo $event['user']; ?></strong><br>
                                <?php echo $event['task_type']; ?><br>
                                <?php echo $locationName; ?><br>
                                <?php echo $event['start_time']; ?> - <?php echo $event['end_time']; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
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
            // Fill the first row with empty cells if the month doesn't start on Monday
            $firstDayOfWeek = date("N", strtotime($startDate));
            for ($i = 1; $i < $firstDayOfWeek; $i++) {
                echo '<div class="day empty"></div>';
            }

            $daysInMonth = date("t", strtotime($startDate));
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = date("Y-m-d", strtotime($startDate . " +".($day-1)." days"));
                echo '<div class="day">';
                echo '<strong>' . $day . '</strong>';

                if (isset($scheduleByLocation)) {
                    foreach ($allLocations as $location) {
                        $locationId = $location['location_id'];
                        $locationName = $location['city'];

                        if (isset($scheduleByLocation[$locationId][$date])) {
                            foreach ($scheduleByLocation[$locationId][$date] as $event) {
                                echo '<div class="event" data-location-id="' . $locationId . '" data-user-id="' . $event['user_id'] . '">';
                                echo '<strong>' . $event['user'] . '</strong><br>';
                                echo $event['task_type'] . '<br>';
                                echo $locationName . '<br>';
                                echo $event['start_time'] . ' - ' . $event['end_time'];
                                echo '</div>';
                            }
                        }
                    }
                }

                if (isset($timeOffByDate[$date])) {
                    foreach ($timeOffByDate[$date] as $timeOffEvent) {
                        
                        echo '<div class="event time-off">';
                        echo '<strong>' . $timeOffEvent['user'] . '</strong><br>';
                        echo 'Time Off: ' . $timeOffEvent['reason'] . '<br>';
                        $startTime = new DateTime($timeOffEvent['start_time']);
                        $endTime = new DateTime($timeOffEvent['end_time']);

                        // Check if work schedule details are available
                        if (isset($timeOffEvent['task_type'])) {
                            echo $timeOffEvent['task_type'] . '<br>';
                            echo $timeOffEvent['location']['city'] . '<br>';
                            echo $startTime->format('H:i') . " - " . $endTime->format('H:i');                        }
                        
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
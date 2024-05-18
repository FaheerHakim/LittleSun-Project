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
$workScheduleByLocation = [];

// Fetch work schedule for the selected period
$workSchedule = $scheduleHandler->getWorkScheduleForPeriod($startDate, $endDate);
$allLocations = $locationHandler->getAllLocations();

// Create an associative array to store schedule data by location
$scheduleByLocation = array_fill_keys(array_column($allLocations, 'location_id'), []);
$selectedLocation = isset($_GET['location']) ? $_GET['location'] : 'all';


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

// Initialize variables


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



foreach ($allLocations as $location) {
    $locationId = $location['location_id'];
    $workScheduleByLocation[$locationId] = $scheduleHandler->getWorkScheduleForLocation($startDate, $endDate, $locationId); // Pass locationId here

   foreach ($workScheduleByLocation[$locationId] as $schedule) {
    $date = date("Y-m-d", strtotime($schedule['date']));
    $scheduleByLocation[$locationId][$date][] = [
        'user_id' => $schedule['user_id'],
        'user' => $userHandler->getUserNameById($schedule['user_id']),
        'task_type' => $taskTypeHandler->getTaskTypeNameById($schedule['task_type_id'])['task_type_name'],
        'start_time' => $schedule['start_time'],
        'end_time' => $schedule['end_time']
    ];

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
    <div class="location-dropdown">
        <label for="location">Select Location:</label>
        <select id="location" name="location">
            <option value="all">All Locations</option>
            <?php foreach ($allLocations as $location): ?>
                <option value="<?php echo $location['location_id']; ?>"><?php echo $location['city']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

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
            <?php foreach ($allLocations as $location): ?>
                <?php
                $locationId = $location['location_id'];
                $locationName = $location['city'];
                ?>
                <?php if (isset($scheduleByLocation[$locationId][$startDate])): ?>
                    <?php foreach ($scheduleByLocation[$locationId][$startDate] as $event): ?>
                        <div class="event" data-location-id="<?php echo $locationId; ?>" data-user-id="<?php echo $event['user_id']; ?>">
                            <strong><?php echo $event['user']; ?></strong><br>
                            <?php echo $event['task_type']; ?><br>
                            <?php echo $locationName; ?><br>
                            <?php echo $event['start_time']; ?> - <?php echo $event['end_time']; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No work schedule available for this day at <?php echo $locationName; ?></p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
<?php elseif ($viewType == 'weekly'): ?>
    <!-- Weekly view code here -->
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
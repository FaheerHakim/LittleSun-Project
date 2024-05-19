<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/WorkHours.php";
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/Location.php"; 
require_once __DIR__ . "/classes/TaskType.php"; 
require_once __DIR__ . "/classes/TimeOff.php"; 


$workHoursHandler = new WorkHours();
$userHandler = new User();
$locationHandler = new Location(); 
$taskTypeHandler = new TaskType();
$timeOffHandler = new TimeOff();

$selectedUsers = isset($_POST['users']) ? $_POST['users'] : [];
$period = isset($_POST['period']) ? $_POST['period'] : "";
$year = isset($_POST['year_select']) ? $_POST['year_select'] : "";
$month = isset($_POST['month_select']) ? $_POST['month_select'] : "";
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : "";
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : "";
$location = isset($_POST['location']) ? $_POST['location'] : "";
$taskType = isset($_POST['task_type']) ? $_POST['task_type'] : "";
$overtime = isset($_POST['overtime']) ? $_POST['overtime'] : "";

$timeOff = isset($_POST['time_off']) ? $_POST['time_off'] : "";
$timeOffQuery = "SELECT * FROM time_off_requests WHERE 1";

$query = "SELECT work_hours.*, work_schedule.location_id, work_schedule.task_type_id, work_schedule.start_time AS planned_start_time, work_schedule.end_time AS planned_end_time 
          FROM work_hours 
          INNER JOIN work_schedule ON work_hours.user_id = work_schedule.user_id 
          WHERE 1";

if (!empty($timeOff) && $timeOff != 'all') {
    if ($timeOff == 'yes') {
        // Include only time off days
        $query .= " AND EXISTS (
                        SELECT 1 
                        FROM time_off_requests 
                        WHERE time_off_requests.user_id = work_hours.user_id 
                        AND DATE(work_hours.start_time) BETWEEN time_off_requests.start_date AND time_off_requests.end_date
                    )";
    } elseif ($timeOff == 'no') {
        // Exclude time off days
        $query .= " AND NOT EXISTS (
                        SELECT 1 
                        FROM time_off_requests 
                        WHERE time_off_requests.user_id = work_hours.user_id 
                        AND DATE(work_hours.start_time) BETWEEN time_off_requests.start_date AND time_off_requests.end_date
                    )";
    }
}

if (in_array('all', $selectedUsers)) {
    $allUsers = $userHandler->getAllUsers(); 
    $selectedUsers = array_column($allUsers, 'user_id');
}

if (!empty($selectedUsers) && $selectedUsers[0] != 'all') {
    $usersStr = implode(",", $selectedUsers);
    $query .= " AND work_hours.user_id IN ($usersStr)";
}

if ($period == 'year' && !empty($year)) {
    $query .= " AND YEAR(work_hours.start_time) = $year";
} elseif ($period == 'month' && !empty($month)) {
    $query .= " AND MONTH(work_hours.start_time) = $month";
} elseif ($period == 'custom' && !empty($startDate) && !empty($endDate)) {
    $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
    $query .= " AND work_hours.start_time BETWEEN '$startDate' AND '$endDate'";
}

if (!empty($location) && $location != 'all') {
    $query .= " AND work_schedule.location_id = $location";
}

if (!empty($taskType) && $taskType != 'all') {
    $query .= " AND work_schedule.task_type_id = $taskType"; 
} 

if (!empty($overtime) && $overtime != 'all') {
    $query .= " AND work_schedule.overtime = $overtime"; 
}

$reportData = $workHoursHandler->executeCustomQuery($query);
$timeOffData = $timeOffHandler->executeCustomQuery($timeOffQuery);

$totalWorkedHours = 0;

$totalOvertimeMinutes = 0; // To accumulate total overtime

// Loop through each row of the report data
foreach ($reportData as $row) {
    // Calculate the worked hours for the current shift
    $startTime = new DateTime($row['start_time']);
    $endTime = new DateTime($row['end_time']);

    // Calculate the difference in seconds
    $secondsDiff = $endTime->getTimestamp() - $startTime->getTimestamp();

    // Round up minutes based on seconds
    $minutes = ceil($secondsDiff / 60);

    // Accumulate the total worked hours
    $totalWorkedHours += $minutes;

    // Calculate overtime
    $plannedStartTime = new DateTime($row['planned_start_time']);
    $plannedEndTime = new DateTime($row['planned_end_time']);
    $plannedStartTime->setTime($plannedStartTime->format('H'), $plannedStartTime->format('i'));
    $plannedEndTime->setTime($plannedEndTime->format('H'), $plannedEndTime->format('i'));
    $plannedDuration = $plannedEndTime->getTimestamp() - $plannedStartTime->getTimestamp();

    $actualStartTime = new DateTime($row['start_time']);
    $actualEndTime = new DateTime($row['end_time']);
    $actualStartTime->setTime($actualStartTime->format('H'), $actualStartTime->format('i'));
    $actualEndTime->setTime($actualEndTime->format('H'), $actualEndTime->format('i'));
    $actualDuration = $actualEndTime->getTimestamp() - $actualStartTime->getTimestamp();

    $isOvertime = $actualDuration > $plannedDuration;
    $overtimeDuration = $isOvertime ? $actualDuration - $plannedDuration : 0;

    if ($overtimeDuration > 0) {
        $overtimeMinutes = ceil($overtimeDuration / 60);
        $totalOvertimeMinutes += $overtimeMinutes; // Accumulate total overtime minutes
    }
}

// Convert the total worked hours to hours and minutes format
$totalHours = floor($totalWorkedHours / 60);
$totalMinutes = $totalWorkedHours % 60;
$totalWorkedHoursFormatted = sprintf("%02d:%02d", $totalHours, $totalMinutes);

// Convert the total overtime to hours and minutes format
$totalOvertimeHours = floor($totalOvertimeMinutes / 60);
$totalOvertimeMinutes %= 60;
$totalOvertimeFormatted = sprintf("%02d:%02d", $totalOvertimeHours, $totalOvertimeMinutes);

$totalSickTimeMinutes = 0;
foreach ($timeOffData as $row) {
    $workSchedule = $workHoursHandler->getWorkScheduleForUserAndDate($row['user_id'], $row['start_date']);

    $plannedStartTime = new DateTime($workSchedule['start_time']);
    $plannedEndTime = new DateTime($workSchedule['end_time']);
    $plannedDuration = $plannedEndTime->diff($plannedStartTime);
    $plannedDuration->format('%H:%I');
}

// Convert total sick time to hours and minutes format

$plannedDurationFormatted = $plannedDuration->format('%H:%I');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Result</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h2>Report Result</h2>
<?php if ($timeOff !== 'yes'): ?>
    <p><strong>Total Worked Hours: <?php echo $totalWorkedHoursFormatted; ?></strong></p>
    <p><strong>Total Overtime: <?php echo $totalOvertimeFormatted; ?></strong></p>
<?php endif; ?>
    <?php if ($timeOff == 'yes'): ?>
        <h3>Total Sick Time: <?php echo $plannedDurationFormatted . " hours"; ?></h3>

    <?php endif; ?>
    <table>
        <tr>
            <?php if (!empty($selectedUsers) && $selectedUsers[0] != 'all'): ?>
                <th>Employee Name</th>
            <?php endif; ?>
            <?php if ($timeOff == 'yes'): ?>
                <th>Reason</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Scheduled Start Time</th>
                <th>Scheduled End Time</th>
                <th>Total sick time</th>
            <?php else: ?>  
                <?php if (!empty($location) && $location == 'all'): ?>
                    <th>Location</th>
                <?php endif; ?> 
                <?php if (!empty($taskType) && $taskType == 'all'): ?>           
                    <th>Task Type</th>
                <?php endif; ?> 
                <th>Start Time</th>
                <th>End Time</th>
                <th>Total Worked Hours per shift</th>
                <?php if (!empty($overtime) && $overtime == 'all'): ?>           
                    <th>Overtime</th>
                    <th>Overtime Duration</th>
                <?php endif; ?> 
            <?php endif; ?>
        </tr>
            <?php if ($timeOff == 'yes'): ?>
            <?php foreach ($timeOffData as $row): ?>
                <?php
        // Get the user details based on user_id
        $user = $userHandler->getUserById($row['user_id']);
        ?>
        <td><?php echo isset($user['first_name']) ? $user['first_name'] : 'Unknown'; ?> <?php echo isset($user['last_name']) ? $user['last_name'] : ''; ?></td>
        <td>
            <?php
            echo $row['reason'];
            if ($row['reason'] === 'Other') {
                // Display additional notes if the reason is 'other'
                echo "<br>Additional Notes: " . $row['additional_notes'];
            }
            ?>
        </td>
        <td><?php echo $row['start_date']; ?></td>
        <td><?php echo $row['end_date']; ?></td>
      
        <?php
        // Check if the user has a scheduled work date on the days they took time off
        $workSchedule = $workHoursHandler->getWorkScheduleForUserAndDate($row['user_id'], $row['start_date']);
        if ($workSchedule) {
            // Display the start and end time of the user's work schedule
            ?>
            <td><?php echo $workSchedule['start_time']; ?></td>
            <td><?php echo $workSchedule['end_time']; ?></td>
        <?php 
        } else {
            // No scheduled work date found, display empty cells
            ?>
        <?php } ?>
        <td>
        <?php
        // Calculate the planned time for the shift
        $plannedStartTime = new DateTime($workSchedule['start_time']);
        $plannedEndTime = new DateTime($workSchedule['end_time']);
        $plannedDuration = $plannedEndTime->diff($plannedStartTime);
        echo $plannedDuration->format('%H:%I');
            ?>
        </td>
                    </tr>
            <?php endforeach; ?>
        <?php else: ?>
        <?php foreach ($reportData as $row): ?>
            <tr>
                <?php if (!empty($selectedUsers) && $selectedUsers[0] != 'all'): ?>
                    <td>
                        <?php
                        $user = $userHandler->getUserById($row['user_id']);
                        echo $user['first_name'] . ' ' . $user['last_name'];
                        ?>
                    </td>
                    <?php endif; ?>
                <?php if (!empty($location) && $location = 'all'): ?>
                    <td>
                        <?php
                        $location = $locationHandler->getLocationById($row['location_id']);
                        echo $location ? $location['city'] : 'Unknown'; 
                        ?>
                    </td>
                <?php endif; ?>
                <?php if (!empty($taskType) && $taskType = 'all'): ?>
                <td>
                    <?php
                    $taskType = $taskTypeHandler->getTaskTypeNameById($row['task_type_id']);
                    echo $taskType ? $taskType['task_type_name'] : 'Unknown'; // Assuming 'task_type_name' is the column for the task type name
                    ?>
                </td>
                <?php endif; ?>
                <td><?php echo $row['start_time']; ?></td>
                <td><?php echo $row['end_time']; ?></td>
                <td>
                <?php
                    $startTime = new DateTime($row['start_time']);
                    $endTime = new DateTime($row['end_time']);

                    // Calculate the difference in seconds
                    $secondsDiff = $endTime->getTimestamp() - $startTime->getTimestamp();

                    // Round up minutes based on seconds
                    $minutes = ceil($secondsDiff / 60);

                    // Format hours and minutes
                    $hours = floor($minutes / 60);
                    $minutes %= 60;

                    // Format the worked hours
                    $workedHours = sprintf("%02d:%02d", $hours, $minutes);
                    echo $workedHours;
                ?>
                </td>
                <?php
                $plannedStartTime = new DateTime($row['planned_start_time']);
                $plannedEndTime = new DateTime($row['planned_end_time']);
                $plannedStartTime->setTime($plannedStartTime->format('H'), $plannedStartTime->format('i'));
                $plannedEndTime->setTime($plannedEndTime->format('H'), $plannedEndTime->format('i'));
                $plannedDuration = $plannedEndTime->getTimestamp() - $plannedStartTime->getTimestamp();

                $actualStartTime = new DateTime($row['start_time']);
                $actualEndTime = new DateTime($row['end_time']);
                $actualStartTime->setTime($actualStartTime->format('H'), $actualStartTime->format('i'));
                $actualEndTime->setTime($actualEndTime->format('H'), $actualEndTime->format('i'));
                $actualDuration = $actualEndTime->getTimestamp() - $actualStartTime->getTimestamp();

                $isOvertime = $actualDuration > $plannedDuration;
                $overtimeDuration = $isOvertime ? $actualDuration - $plannedDuration : 0;

                if ($overtimeDuration > 0) {
                    $overtimeMinutes = ceil($overtimeDuration / 60);
                    $overtimeHours = floor($overtimeMinutes / 60);
                    $overtimeMinutes %= 60;
                    $overtimeFormatted = sprintf("%02d:%02d", $overtimeHours, $overtimeMinutes);
                } else {
                    $overtimeFormatted = '00:00';
                }
                ?>
                <?php if (!empty($overtime) && $overtime = 'all'): ?>
                    <td><?php echo $isOvertime ? 'Yes' : 'No'; ?></td>
                    <td><?php echo $overtimeFormatted; ?></td>
                <?php endif; ?>

            </tr>
        <?php endforeach; ?>
    <?php endif; ?> 
    </table>
</body>
</html>
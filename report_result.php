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

if (!empty($timeOff)) {
    if ($timeOff == 'yes') {
        $query .= " AND EXISTS (
                        SELECT 1 
                        FROM time_off_requests 
                        WHERE time_off_requests.user_id = work_hours.user_id 
                        AND DATE(work_hours.start_time) BETWEEN time_off_requests.start_date AND time_off_requests.end_date
                    )";
    } elseif ($timeOff == 'no') {
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
if ($overtime == 'yes') {
    $query .= " AND (work_schedule.end_time - work_schedule.start_time) < (work_hours.end_time - work_hours.start_time)";
}

$reportData = $workHoursHandler->executeCustomQuery($query);
$timeOffData = $timeOffHandler->executeCustomQuery($timeOffQuery);

$totalWorkedMinutes = 0;
$totalOvertimeMinutes = 0; 
$displayedWorkHours = [];
$totalTimeOffMinutes = 0;

$uniqueUsers = [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report result</title>
    <link rel="stylesheet" href="styles/report_result.css">
</head>
<body>
<a href="generate.php" class="go-back-button" type="button">Go Back</a>
    
<h1>Report result</h1>
<div id="userContainer">
    <div class="metrics">
    <?php if ($timeOff != 'yes'): ?>
        <div class="info-square"><b>Total worked hours</b> <br> 
            <?php
            ?>
        </div>
        <?php endif; ?>
        <?php if ($timeOff == 'yes'): ?>
            <div class="info-square"><b>Total time-off hours</b> 
            <?php
            // Calculate total time-off hours
            foreach ($timeOffData as $row) {
                $workSchedule = $workHoursHandler->getWorkScheduleForUserAndDate($row['user_id'], $row['start_date']);
                if ($workSchedule) {
                    $plannedStartTime = new DateTime($workSchedule['start_time']);
                    $plannedEndTime = new DateTime($workSchedule['end_time']);
                    $plannedDuration = $plannedEndTime->diff($plannedStartTime);
                    $totalTimeOffMinutes += ($plannedDuration->h * 60) + $plannedDuration->i;
                }
            }

            $totalTimeOffHours = floor($totalTimeOffMinutes / 60);
            $remainingMinutes = $totalTimeOffMinutes % 60;
            echo sprintf("%02d:%02d hours", $totalTimeOffHours, $remainingMinutes);
            ?>
            </div>
        <?php endif; ?>
        <?php if ($overtime == 'yes'): ?>
            <div class="info-square"><b>Total overtime</b> <br>
        <?php
        ?>
        </div>
        <?php endif; ?>
        <div class="info-square"><b>Total Users</b> 
        <?php
            foreach ($reportData as $row) {
                $uniqueUsers[$row['user_id']] = true;
            }
            foreach ($timeOffData as $row) {
                $uniqueUsers[$row['user_id']] = true;
            }
            $userCount = count($uniqueUsers);
            echo $userCount;
        ?>
        </div>
    </div>
    
    <table>
    <tr>
        <?php if (!empty($selectedUsers) && $selectedUsers[0] != 'all'): ?>
            <th>Employee Name</th>
        <?php endif; ?>
        <?php if ($timeOff == 'yes'): ?>
            <th>Reason</th>
            <th>Start Date sick leave</th>
            <th>End Date sick leave</th>
            <th>Scheduled Start Time</th>
            <th>Scheduled End Time</th>
            <th>Total Time-Off hours</th>
        <?php else: ?>  
            <?php if (!empty($location) ): ?>
                <th>Location</th>
            <?php endif; ?> 
            <?php if (!empty($taskType) && $taskType != ''): ?>           
                <th>Task Type</th>
            <?php endif; ?> 
            <th>Worked Start Time</th>
            <th>Worked End Time</th>
            <th>Total Worked Hours</th>
            <?php if (!empty($overtime) &&  $overtime == 'yes'): ?>       
                <th>Scheduled Start Time</th>
                <th>Scheduled End Time</th>    
                <th>Total Scheduled Hours</th>    
                <th>Overtime</th>
                <th>Overtime Duration</th>
            <?php endif; ?> 
        <?php endif; ?>
    </tr>
    <?php if ($timeOff == 'yes'): ?>
        <?php foreach ($timeOffData as $row): ?>
            <?php
            $user = $userHandler->getUserById($row['user_id']);
            ?>
            <tr>
                <td><?php echo isset($user['first_name']) ? $user['first_name'] : 'Unknown'; ?> <?php echo isset($user['last_name']) ? $user['last_name'] : ''; ?></td>
                <td>
                    <?php
                    echo $row['reason'];
                    if ($row['reason'] === 'Other') {
                        echo "<br>Additional Notes: " . $row['additional_notes'];
                    }
                    ?>
                </td>
                <td><?php echo $row['start_date']; ?></td>
                <td><?php echo $row['end_date']; ?></td>
                <?php
                $workSchedule = $workHoursHandler->getWorkScheduleForUserAndDate($row['user_id'], $row['start_date']);
                if ($workSchedule) {
                    ?>
                    <td><?php echo $workSchedule['start_time']; ?></td>
                    <td><?php echo $workSchedule['end_time']; ?></td>
                    <td>
                    <?php
                    $plannedStartTime = new DateTime($workSchedule['start_time']);
                    $plannedEndTime = new DateTime($workSchedule['end_time']);
                    $plannedDuration = $plannedEndTime->diff($plannedStartTime);
                    echo $plannedDuration->format('%H:%I') . " hours";
                    ?>
                    </td>
                <?php } else { ?>
                    <td colspan="3">No schedule</td>
                <?php } ?>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <?php foreach ($reportData as $row): ?>
            <?php
            if (in_array($row['work_hours_id'], $displayedWorkHours)) {
                continue; 
            }
            $displayedWorkHours[] = $row['work_hours_id']; 

            $startTime = new DateTime($row['start_time']);
            $endTime = new DateTime($row['end_time']);

            $secondsDiff = $endTime->getTimestamp() - $startTime->getTimestamp();

            $minutes = ceil($secondsDiff / 60);

            $totalWorkedMinutes += $minutes;

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

            $overtimeFormatted = '00:00';
            if ($overtimeDuration > 0) {
                $overtimeMinutes = ceil($overtimeDuration / 60);
                $totalOvertimeMinutes += $overtimeMinutes; 

                $overtimeHours = floor($overtimeMinutes / 60);
                $overtimeMinutes %= 60;
                $overtimeFormatted = sprintf("%02d:%02d", $overtimeHours, $overtimeMinutes);
            }
            ?>
            <tr>
                <?php if (!empty($selectedUsers) && $selectedUsers[0] != 'all'): ?>
                    <td>
                        <?php
                        $user = $userHandler->getUserById($row['user_id']);
                        echo $user['first_name'] . ' ' . $user['last_name'];
                        ?>
                    </td>
                <?php endif; ?>
                <?php if (!empty($location)): ?>
                    <td>
                        <?php
                        $location = $locationHandler->getLocationById($row['location_id']);
                        echo $location ? $location['city'] : 'Unknown'; 
                        ?>
                    </td>
                <?php endif; ?>
                <?php if (!empty($taskType) && $taskType != ''): ?>
                <td>
                    <?php
                    $taskType = $taskTypeHandler->getTaskTypeNameById($row['task_type_id']);
                    echo $taskType ? $taskType['task_type_name'] : 'Unknown'; 
                    ?>
                </td>
                <?php endif; ?>
                <td><?php echo $row['start_time']; ?></td>
                <td><?php echo $row['end_time']; ?></td>
                <td>
                <?php
                    $startTime = new DateTime($row['start_time']);
                    $endTime = new DateTime($row['end_time']);

                    $secondsDiff = $endTime->getTimestamp() - $startTime->getTimestamp();

                    $minutes = ceil($secondsDiff / 60);

                    $hours = floor($minutes / 60);
                    $minutes %= 60;

                    $workedHours = sprintf("%02d:%02d", $hours, $minutes);
                    echo $workedHours . " hours";
                ?>
                </td>
                <?php if (!empty($overtime) && $overtime == 'yes'): ?>
                    <td><?php echo $row['planned_start_time']; ?></td>
                    <td><?php echo $row['planned_end_time']; ?></td>
                    <td>
                        <?php
                        $plannedDuration = (new DateTime($row['planned_end_time']))->diff(new DateTime($row['planned_start_time']));
                        echo $plannedDuration->format('%H:%I') . " hours";
                        ?>
                    </td>
                    <td><?php echo $isOvertime ? 'Yes' : 'No'; ?></td>
                    <td><?php echo $overtimeFormatted . " hours"; ?></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?> 
    </table>
</div>
<?php
    if ($timeOff != 'yes') {

    $totalHours = floor($totalWorkedMinutes / 60);
    $totalMinutes = $totalWorkedMinutes % 60;
    $totalWorkedHoursFormatted = sprintf("%02d:%02d", $totalHours, $totalMinutes);
    echo "<script>document.querySelector('.info-square b').nextSibling.textContent = '$totalWorkedHoursFormatted hours';</script>";
}
    
    if ($overtime == 'yes') {
    $totalOvertimeHours = floor($totalOvertimeMinutes / 60);
    $totalOvertimeMinutes = $totalOvertimeMinutes % 60;
    $totalOvertimeFormatted = sprintf("%02d:%02d", $totalOvertimeHours, $totalOvertimeMinutes);
    echo "<script>document.querySelector('.metrics .info-square:nth-child(2) b').nextSibling.textContent = '$totalOvertimeFormatted hours';</script>";
}
?>
</body>
</html>

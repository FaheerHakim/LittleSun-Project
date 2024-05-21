<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/Schedule.php"; // Update to use Schedule class
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/TaskType.php"; // Include the TaskType class
require_once __DIR__ . "/classes/TimeOff.php";

$scheduleHandler = new Schedule();
$userHandler = new User();
$taskTypeHandler = new TaskType(); // Instantiate the TaskType class
$timeOffHandler = new TimeOff();

include 'logged_in.php';
include 'permission_manager.php';

$employeeUsers = $scheduleHandler->getEmployeeUsers();
$locations = $scheduleHandler->getLocations();
$allTaskTypes = $taskTypeHandler->getTaskTypes(); // Get all task types

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['task_type_id']) && isset($_POST['location_id']) && isset($_POST['date']) && isset($_POST['start_time']) && isset($_POST['end_time'])) {
    $userId = $_POST['user_id'];
    $taskTypeId = $_POST['task_type_id'];
    $locationId = $_POST['location_id'];
    $date = $_POST['date'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];

    // Debugging information
    error_log("Assigning schedule: UserID=$userId, TaskTypeID=$taskTypeId, LocationID=$locationId, Date=$date, StartTime=$startTime, EndTime=$endTime");

    $result = $scheduleHandler->assignTaskSchedule($userId, $taskTypeId, $locationId, $date, $startTime, $endTime);

    if ($result) {
        // Set success message in session and redirect
        $_SESSION['message'] = 'Work schedule assigned successfully.';
        $_SESSION['message_type'] = 'success';
        header("Location: message_work_schedule.php");
        exit();
    } else {
        // Set error message in session and redirect
        $_SESSION['message'] = 'Failed to assign work schedule.';
        $_SESSION['message_type'] = 'error';
        header("Location: message_work_schedule.php");
        exit();
    }
}

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $user = null;
    foreach ($employeeUsers as $empUser) {
        if ($empUser['user_id'] == $userId) {
            $user = $empUser;
            break;
        }
    }
    if ($user) {
        $assignedTaskTypes = $userHandler->getAssignedTaskTypes($userId);
        $assignedTaskTypeIds = array_column($assignedTaskTypes, 'task_type_id');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Work Schedule</title>
    <link rel="stylesheet" href="styles/assign_work.css">
    <script>
        function disableSubmitButton() {
            document.getElementById("assign-button").disabled = true;
        }
    </script>
</head>
<body>
    <a href="assign_employees.php" class="go-back-button" type="button">Go Back</a>
    <h1>Assign Work Schedule to <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h1>
<div class="assign-schedule-container">
    <div class="assign-schedule">
        <?php if (empty($assignedTaskTypes)): ?>
            <p>No task types assigned to this user.</p>
            <button onclick="location.href='schedule_manager.php'">Overview work schedule</button>
        <?php else: ?>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form action="assign_work.php" method="post" onsubmit="disableSubmitButton()">
                <label for="task_type_id">Assigned task types:</label>
                <select name="task_type_id" id="task_type_id">
                    <?php foreach ($assignedTaskTypes as $taskType): ?>
                        <option value="<?php echo htmlspecialchars($taskType['task_type_id']); ?>"><?php echo htmlspecialchars($taskType['task_type_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="location_id">Location:</label>
                <select name="location_id" id="location_id">
                    <?php foreach ($locations as $location): ?>
                        <option value="<?php echo htmlspecialchars($location['location_id']); ?>"><?php echo htmlspecialchars($location['city']); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required><br>
                <label for="start_time">Start Time:</label>
                <input type="time" id="start_time" name="start_time" required><br>
                <label for="end_time">End Time:</label>
                <input type="time" id="end_time" name="end_time" required><br>
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userId); ?>">
                <button class="assign-button" id="assign-button" type="submit">Assign Work Schedule</button>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

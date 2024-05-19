<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/WorkHours.php";
require_once __DIR__ . "/classes/Location.php";
require_once __DIR__ . "/classes/TaskType.php";

$userHandler = new User();
$locationHandler = new Location();
$taskTypeHandler = new TaskType();

include_once 'logged_in.php';

// Fetch all users, locations, and task types
$users = $userHandler->getAllUsers();
$locations = $locationHandler->getAllLocations();
$taskTypes = $taskTypeHandler->getAllTaskTypes();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="script/multiselect-dropdown.js" defer></script>
    <title>Generate Report</title>
</head>
<body>
    <h2>Generate Report</h2>
    <form action="report_result-copy.php" method="post" onsubmit="return validateForm();">
    <div id="error-message" style="color: red; margin-bottom: 10px;"></div>

        <label for="users">Users:</label>
        <select name="users[]" id="users" multiple multiselect-search="true">
            <option value="all">All Employees</option>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['user_id']; ?>"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <label for="period">Period:</label>
        <input type="radio" name="period" value="year" id="year" checked>
        <label for="year">Year </label>
        <select name="year_select" id="year_select" class="year_select" style="display: none;">
            <?php for ($i = date("Y"); $i >= 2000; $i--): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>

        <input type="radio" name="period" value="month" id="month">
        <label for="month">Month</label>
        <select name="month_select" id="month_select" class="month_select" style="display: none;">
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>"><?php echo date("F", mktime(0, 0, 0, $i, 10)); ?></option>
            <?php endfor; ?>
        </select>
        <input type="radio" name="period" value="custom" id="custom">
        <label for="custom">Custom Period</label>
        <br>
        <div id="custom_period" style="display: none;">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" id="start_date">
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" id="end_date">
        </div>

        <label for="location">Location:</label>
        <select name="location" id="location" multiple multiselect-search="true">
            <option value="all">All Locations</option>
            <?php foreach ($locations as $location): ?>
                <option value="<?php echo $location['location_id']; ?>"><?php echo $location['city']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>

<label for="task_type">Task Type:</label>
<select name="task_type" id="task_type" multiple multiselect-search="true">
    <option value="all">All Task Types</option>
    <?php foreach ($taskTypes as $taskType): ?>
        <option value="<?php echo $taskType['task_type_id']; ?>"><?php echo $taskType['task_type_name']; ?></option>
    <?php endforeach; ?>
</select>
        <br>

        <label for="overtime">Overtime:</label>
        <input type="radio" name="overtime" value="yes" id="overtime_yes"><label for="overtime_yes">Yes</label>
        <input type="radio" name="overtime" value="no" id="overtime_no"><label for="overtime_no">No</label>
        <br>

        <button type="submit">Generate Report</button>
    </form>

    <script>
        function validateForm() {
            const errorMessage = document.getElementById('error-message');
            errorMessage.innerHTML = '';
            // Check users
            const users = document.getElementById('users');
            const usersSelected = Array.from(users.options).some(option => option.selected && option.value == 'all');

            // Check location
            const location = document.getElementById('location');
            const locationSelected = Array.from(location.options).some(option => option.selected && option.value == 'all');

            // Check task type
            const taskType = document.getElementById('task_type');
            const taskTypeSelected = Array.from(taskType.options).some(option => option.selected && option.value == 'all');

            if (!usersSelected && !locationSelected && !taskTypeSelected) {
                errorMessage.innerHTML = 'Please select at least one of the following: Users, Location, or Task Type.';
                return false;
            }

            return true;
        }

        document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('year').addEventListener('change', function () {
        document.getElementById('year_select').style.display = 'block';
        document.getElementById('month_select').style.display = 'none';
        document.getElementById('custom_period').style.display = 'none';
    });

    document.getElementById('month').addEventListener('change', function () {
        document.getElementById('year_select').style.display = 'none';
        document.getElementById('month_select').style.display = 'block';
        document.getElementById('custom_period').style.display = 'none';
    });

    document.getElementById('custom').addEventListener('change', function () {
        document.getElementById('year_select').style.display = 'none';
        document.getElementById('month_select').style.display = 'none';
        document.getElementById('custom_period').style.display = 'block';
    });
});
    </script>
</body>
</html>

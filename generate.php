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

$users = $userHandler->getAllUsers();
$locations = $locationHandler->getAllLocations();
$taskTypes = $taskTypeHandler->getAllTaskTypes();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/generate.css">
    <script src="script/multiselect-dropdown.js" defer></script>
    <title>Generate Report</title>
</head>
<body>
<a href="reports.php" class="go-back-button" type="button">Go Back</a>
    <h1 class="generate-report-heading">Generate Report</h1>
    <div class="container">
    <div class="generate-report-container">
        <form action="report_result.php" method="post" onsubmit="return validateForm();" class="report-form">
            <div id="error-message" class="error-message"></div>

            <div class="form-group">
                <label for="users">Users:</label>
                <select name="users[]" id="users" multiple class="multiselect-dropdown" multiselect-search="true">
                    <option value="all">All Employees</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['user_id']; ?>"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="period">Period:</label>
                <div class="period-container">
                    <input type="radio" name="period" value="year" id="year" checked>
                    <label for="year">Year </label>
                    <select name="year_select" id="year_select" class="period-select year-select" style="display: none;">
                        <?php for ($i = date("Y"); $i >= 2000; $i--): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
    
                    <input type="radio" name="period" value="month" id="month">
                    <label for="month">Month</label>
                    <select name="month_select" id="month_select" class="period-select month-select" style="display: none;">
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>"><?php echo date("F", mktime(0, 0, 0, $i, 10)); ?></option>
                        <?php endfor; ?>
                    </select>
                    <input type="radio" name="period" value="custom" id="custom">
                    <label for="custom">Custom Period</label>
                </div>
                </div>

            <div id="custom_period" class="custom-period" style="display: none;">
                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="date" name="start_date" id="start_date">
                </div>
                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" name="end_date" id="end_date">
                </div>
            </div>

            <div class="form-group">
                <label for="location">Location:</label>
                <select name="location" id="location" multiple class="multiselect-dropdown" multiselect-search="true">
                    <option value="all">All Locations</option>
                    <?php foreach ($locations as $location): ?>
                        <option value="<?php echo $location['location_id']; ?>"><?php echo $location['city']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="task_type">Task Type:</label>
                <select name="task_type" id="task_type" multiple class="multiselect-dropdown" multiselect-search="true">
                    <option value="all">All Task Types</option>
                    <?php foreach ($taskTypes as $taskType): ?>
                        <option value="<?php echo $taskType['task_type_id']; ?>"><?php echo $taskType['task_type_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="overtime">Overtime:</label>
                <select name="overtime" id="overtime" class="dropdown">
                    <option value="">Select</option>
                    <option value="all">Both</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="form-group">
                <label for="time_off">Time Off:</label>
                <select name="time_off" id="time_off" class="dropdown">
                    <option value="">Select</option>
                    <option value="yes">Yes</option>
                </select>
            </div>

            <button type="submit" class="submit-button">Generate Report</button>
        </form>
    </div>
</div>
    <script>
      function validateForm() {
    const errorMessage = document.getElementById('error-message');
    errorMessage.innerHTML = '';

    // Check users
    const users = document.getElementById('users');
    const usersSelected = Array.from(users.options).some(option => option.selected);

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

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'logged_in.php';

require_once __DIR__ . "/classes/WorkHours.php"; // Include the WorkHours class
require_once __DIR__ . "/classes/TaskType.php"; // Include the TaskType class
require_once __DIR__ . "/classes/User.php"; // Include the User class

// Create an instance of the WorkHours class
$workHoursHandler = new WorkHours();
$taskTypeHandler = new TaskType();
$userHandler = new User();

// Get the user ID from the session (assuming the user is logged in)
$user = $_SESSION['user'];
$userId = $user['user_id']; // Assuming user_id is stored in the 'user_id' key of the user array

// Get all persons and task types for dropdowns
$persons = $userHandler->getEmployeeUsers();
$taskTypes = $taskTypeHandler->getTaskTypes();

// Initialize filtered work hours data
$filteredWorkHoursData = [];

// Handle filter form submission
if (isset($_POST['filter'])) {
    // Get filter values
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $personId = $_POST['person'];
    $taskTypeId = $_POST['task_type'];
    $includeOvertime = isset($_POST['overtime']);

    // Filter work hours data based on the selected criteria
    $filteredWorkHoursData = $workHoursHandler->getFilteredWorkHours($startDate, $endDate, $personId, $taskTypeId, $includeOvertime);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Filtered Work Hours Report</title>
    <link rel="stylesheet" href="styles/clock_in_out.css">
</head>
<body>
<a href="dashboard.php" id="goback-button">Go Back</a>

<!-- Filter form -->
<form method="post">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date">

    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date">

    <label for="month">Month:</label>
    <select name="month" id="month">
        <option value="">All Months</option>
        <?php for ($i = 1; $i <= 12; $i++): ?>
            <option value="<?php echo $i; ?>"><?php echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
        <?php endfor; ?>
    </select>

    <label for="year">Year:</label>
    <select name="year" id="year">
        <option value="">All Years</option>
        <?php for ($i = date('Y'); $i >= date('Y') - 10; $i--): ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
        <?php endfor; ?>
    </select>

    <label for="person">Person:</label>
    <select name="person" id="person">
        <option value="">All Persons</option>
        <!-- Populate dropdown with person options -->
        <?php foreach ($persons as $person): ?>
            <option value="<?php echo $person['user_id']; ?>"><?php echo $person['first_name'] . ' ' . $person['last_name']; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="task_type">Task Type:</label>
    <select name="task_type" id="task_type">
        <option value="">All Task Types</option>
        <!-- Populate dropdown with task type options -->
        <?php foreach ($taskTypes as $taskType): ?>
            <option value="<?php echo $taskType['task_type_id']; ?>"><?php echo $taskType['task_type_name']; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="overtime">Include Overtime:</label>
    <input type="checkbox" id="overtime" name="overtime" value="1">

    <button type="submit" name="filter">Apply Filters</button>
</form>

<!-- Display filtered work hours data -->
<?php if (!empty($filteredWorkHoursData)): ?>
    <!-- Display filtered work hours data in a table -->
    <table>
        <!-- Table headers -->
        <tr>
            <th>Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Total Hours</th>
        </tr>
        <!-- Display filtered work hours data rows -->
        <?php foreach ($filteredWorkHoursData as $workHour): ?>
            <tr>
                <td><?php echo date('Y-m-d', strtotime($workHour['start_time'])); ?></td>
                <td><?php echo date('H:i', strtotime($workHour['start_time'])); ?></td>
                <td><?php echo date('H:i', strtotime($workHour['end_time'])); ?></td>
                <td><?php echo $workHour['total_hours']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No work hours data available for the selected criteria.</p>
<?php endif; ?>
</body>
</html>

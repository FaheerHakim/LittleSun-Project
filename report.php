<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/Report.php"; // Import the Report class

// Initialize the Report object
$reportHandler = new Report();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get filter values
    $locationId = $_POST['location_id'] ?? null;
    $userId = $_POST['user_id'] ?? null;
    $taskTypeId = $_POST['task_type_id'] ?? null;
    $overtime = $_POST['overtime'] ?? null;

    // Generate report using Report class
    $reportData = $reportHandler->generateReport($locationId, $userId, $taskTypeId, $overtime);
} else {
    // If form is not submitted, initialize report data as empty array
    $reportData = [];
}

// Fetch locations, users, and task types for filter options
$locations = $reportHandler->getLocations();
$users = $reportHandler->getUsers();
$taskTypes = $reportHandler->getTaskTypes();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Reports</title>
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
    <h2>Generate Reports</h2>
    <form action="" method="post">
        <label for="location_id">Location:</label>
        <select name="location_id" id="location_id">
            <option value="">Select Location</option>
            <?php foreach ($locations as $location): ?>
                <option value="<?php echo $location['location_id']; ?>"><?php echo $location['city']; ?></option>
            <?php endforeach; ?>
        </select><br>
        <label for="user_id">Person:</label>
        <select name="user_id" id="user_id">
            <option value="">Select Person</option>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['user_id']; ?>"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
            <?php endforeach; ?>
        </select><br>
        <label for="task_type_id">Task Type:</label>
        <select name="task_type_id" id="task_type_id">
            <option value="">Select Task Type</option>
            <?php foreach ($taskTypes as $taskType): ?>
                <option value="<?php echo $taskType['task_type_id']; ?>"><?php echo $taskType['task_type_name']; ?></option>
            <?php endforeach; ?>
        </select><br>
        <label for="overtime">Overtime:</label>
        <select name="overtime" id="overtime">
            <option value="">Select Overtime</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select><br>
        <button type="submit">Generate Report</button>
    </form>

    <h3>Report</h3>
    <table>
        <tr>
            <th>User</th>
            <th>Location</th>
            <th>Task Type</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Overtime</th>
        </tr>
        <?php foreach ($reportData as $row): ?>
            <tr>
                <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                <td><?php echo $row['city']; ?></td>
                <td><?php echo $row['task_type_name']; ?></td>
                <td><?php echo $row['start_time']; ?></td>
                <td><?php echo $row['end_time']; ?></td>
                <td><?php echo $row['overtime'] ? 'Yes' : 'No'; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

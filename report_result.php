<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/WorkHours.php";
require_once __DIR__ . "/classes/User.php";

$workHoursHandler = new WorkHours();
$userHandler = new User(); 

// Extract selected filters from the form submission
$selectedUsers = isset($_POST['users']) ? $_POST['users'] : [];
$period = isset($_POST['period']) ? $_POST['period'] : "";
$year = isset($_POST['year_select']) ? $_POST['year_select'] : "";
$month = isset($_POST['month_select']) ? $_POST['month_select'] : "";
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : "";
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : "";
$location = isset($_POST['location']) ? $_POST['location'] : "";
$taskType = isset($_POST['task_type']) ? $_POST['task_type'] : "";
$overtime = isset($_POST['overtime']) ? $_POST['overtime'] : "";

// Construct the query based on selected filters
$query = "SELECT * FROM work_hours WHERE 1";

if (!empty($selectedUsers) && $selectedUsers[0] != 'all') {
    $usersStr = implode(",", $selectedUsers);
    $query .= " AND user_id IN ($usersStr)";
}

if ($period == 'year' && !empty($year)) {
    $query .= " AND YEAR(start_time) = $year";
} elseif ($period == 'month' && !empty($month)) {
    $query .= " AND MONTH(start_time) = $month";
} elseif ($period == 'custom' && !empty($startDate) && !empty($endDate)) {
    $query .= " AND start_time BETWEEN '$startDate' AND '$endDate'";
}

if ($location != 'all' && $location != 'none') {
    $query .= " AND location_id = $location";
}

if ($taskType != 'all' && $taskType != 'none') {
    $query .= " AND task_type_id = $taskType";
}

if ($overtime == 'yes') {
    $query .= " AND overtime = 1";
} elseif ($overtime == 'no') {
    $query .= " AND overtime = 0";
}

// Execute the query to fetch data from the database
// Assuming you have a method to execute custom queries in your WorkHours class
$reportData = $workHoursHandler->executeCustomQuery($query);

// Display the report
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
    <table>
        <tr>
            <th>Employee Name</th> <!-- Changed from User ID to Employee Name -->
            <th>Start Time</th>
            <th>End Time</th>
            <th>Overtime</th>
        </tr>
        <?php foreach ($reportData as $row): ?>
            <tr>
                <td>
                    <?php
                    // Fetch the user's first name and last name based on the user ID
                    $user = $userHandler->getUserById($row['user_id']);
                    echo $user['first_name'] . ' ' . $user['last_name'];
                    ?>
                </td>
                <td><?php echo $row['start_time']; ?></td>
                <td><?php echo $row['end_time']; ?></td>
                <td><?php echo $row['overtime'] ? 'Yes' : 'No'; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/WorkHours.php";
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/Location.php"; // Include the Location class

$workHoursHandler = new WorkHours();
$userHandler = new User();
$locationHandler = new Location(); // Instantiate the Location class

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
// Construct the query based on selected filters
$query = "SELECT work_hours.*, work_schedule.location_id 
          FROM work_hours 
          INNER JOIN work_schedule ON work_hours.user_id = work_schedule.user_id 
          WHERE 1";

if (!empty($selectedUsers) && $selectedUsers[0] != 'all') {
    $usersStr = implode(",", $selectedUsers);
    $query .= " AND work_hours.user_id IN ($usersStr)";
}

if ($period == 'year' && !empty($year)) {
    $query .= " AND YEAR(work_hours.start_time) = $year";
} elseif ($period == 'month' && !empty($month)) {
    $query .= " AND MONTH(work_hours.start_time) = $month";
} elseif ($period == 'custom' && !empty($startDate) && !empty($endDate)) {
    $query .= " AND work_hours.start_time BETWEEN '$startDate' AND '$endDate'";
}

if ($location != 'all' && $location != 'none') {
    $query .= " AND work_schedule.location_id = $location";
}

if ($taskType != 'all' && $taskType != 'none') {
    $query .= " AND work_schedule.task_type_id = $taskType"; // Assuming task_type_id is in the work_schedule table
}

if ($overtime == 'yes') {
    $query .= " AND work_hours.overtime = 1";
} elseif ($overtime == 'no') {
    $query .= " AND work_hours.overtime = 0";
}

// If 'All Employees' is selected, no need to filter by user_id
if (in_array('all', $selectedUsers)) {
    // Define an empty string for $usersStr
    $usersStr = '';
    $query = str_replace("work_hours.user_id IN ($usersStr)", "1", $query);
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
            <th>Employee Name</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Location</th> <!-- Added Location column header -->
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
                <td>
                    <?php
                    // Fetch the location name based on the location ID
                    $location = $locationHandler->getLocationById($row['location_id']);
                    echo $location ? $location['city'] : 'Unknown'; // Assuming 'city' is the column for the location name
                    ?>
                </td>
                <td><?php echo $row['overtime'] ? 'Yes' : 'No'; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

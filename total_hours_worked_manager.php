<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/WorkHours.php";

$userHandler = new User();
$workHoursHandler = new WorkHours();

include_once 'logged_in.php';

// Fetch all user IDs
$userIds = $workHoursHandler->getAllUserIds();


$workHoursData = [];

foreach ($userIds as $userId) {
    $userData = $userHandler->getUserById($userId);
    $userWorkHours = $workHoursHandler->getWorkHoursForUser($userId);
    $workHoursData[] = [
        'first_name' => $userData['first_name'],
        'last_name' => $userData['last_name'],
        'work_hours' => $userWorkHours
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/total_hours_worked_manager.css">
    <title>Work Hours</title>
  
    </style>
</head>
<body>
<a href="dashboard.php" class="go-back" type="button">Go Back</a>
    <h2>Work Hours</h2>
    <table>
        <tr>
            <th>Full name</th>
            <th>Start Time</th>
            <th>End Time</th>
        </tr>
        <?php foreach ($workHoursData as $userData): ?>
    <tr>
        <td><?php echo $userData['first_name'] . ' ' . $userData['last_name']; ?></td>
        <td>
            <?php foreach ($userData['work_hours'] as $hour): ?>
                <?php echo $hour['start_time']; ?><br>
            <?php endforeach; ?>
        </td>
        <td>
            <?php foreach ($userData['work_hours'] as $hour): ?>
                <?php echo $hour['end_time']; ?><br>
            <?php endforeach; ?>
        </td>
    </tr>
<?php endforeach; ?>
    </table>
</body>
</html>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . "/classes/Schedule.php"; 
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/Location.php";


include 'logged_in.php';

include 'permission_manager.php';

$scheduleHandler = new Schedule();
$user = new User();
$employees = $user->getEmployeeUsers(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $userId = $_POST['user_id'];
    $taskTypeId = $_POST['task_type_id'];
    $locationId = $_POST['location_id'];
    $date = $_POST['date'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];


    if (empty($userId) || empty($taskTypeId) || empty($locationId) || empty($date) || empty($startTime) || empty($endTime)) {
        echo "All fields are required.";
        exit;
    }

  
    $result = $scheduleHandler->assignTaskSchedule($userId, $taskTypeId, $locationId, $date, $startTime, $endTime);
    
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign work</title>
    <link rel="stylesheet" href="styles/assign_employees.css">
    <script src="script/edit_manager.js" defer></script>
 
</head>
<body>
<h1>Assign work</h1>

    <div id="userContainer">
        <input type="text" id="searchBar" placeholder="Search for employees..." onkeyup="searchUsers()">
            <div class="form-group">
                <?php foreach ($employees as $employee): ?>
                <div class="user-box">
                        <?php
                        $profilePicture = !empty($employee['profile_picture']) ? $employee['profile_picture'] : "../LittleSun-Project/images/profile.jpg";
                        ?>                    
                        <img src="<?= htmlspecialchars($profilePicture) ?>" alt="User Profile" class="profile-picture">                        
                        <div class="user-info">
                        <h2><?= htmlspecialchars($employee['first_name'] . " " . $employee['last_name']) ?></h2>
                        <a href="assign_work.php?user_id=<?= htmlspecialchars($employee['user_id']) ?>" class="assign-button" type="button">Assign work</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
    </div>

    <a href="work_schedule_manager.php" class="go-back-button" type="button">Go Back</a>

</body>


</html>

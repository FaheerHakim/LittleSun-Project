<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . "/classes/User.php";

include 'logged_in.php';

include 'permission_manager.php';

$user = new User();
$employees = $user->getEmployeeUsers(); 

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All employees</title>
    <link rel="stylesheet" href="styles/all_employees.css">
    <script src="script/all_employees.js" defer></script>
 
</head>
<body>
<h1>Existing employees</h1>

    <div id="userContainer">
        <input type="text" id="searchBar" placeholder="Search for employees..." onkeyup="searchUsers()">
        <div class="form-group">
            <?php foreach ($employees as $employee): ?>
            <div class="user-box">
                    <?php
                    $profilePicture = !empty($employee['profile_picture']) ? $employee['profile_picture'] : "../LittleSun-Project/images/profile.jpg"; // Default profile picture URL
                    ?>                    
                    <img src="<?= htmlspecialchars($profilePicture) ?>" alt="User Profile" class="profile-picture">                        
                    <div class="user-info">
                    <h2><?= htmlspecialchars($employee['first_name'] . " " . $employee['last_name']) ?></h2>
                 
                    <?php $assignedTaskTypes = $user->getAssignedTaskTypes($employee['user_id']); ?>
                        <?php foreach ($assignedTaskTypes as $task): ?>
                            <?= htmlspecialchars($task['task_type_name']) ?>
                        <?php endforeach; ?>
                  
                    <a href="edit_employee_detail.php?user_id=<?= htmlspecialchars($employee['user_id']) ?>" class="button view" type="button">View profile</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <a href="user.php" class="go-back-button" type="button">Go Back</a>

</body>

</html>

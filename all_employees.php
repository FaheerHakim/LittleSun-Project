<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . "/classes/User.php";

include 'logged_in.php';

include 'permission_manager.php';

$user = new User();
$employees = $user->getEmployeeUsers(); // Get the employee users


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profiles</title>
    <link rel="stylesheet" href="styles/all_employees.css">
    <script src="script/all_employees.js" defer></script>
 
</head>
<body>

    <!-- Search bar -->
    <input type="text" id="searchBar" placeholder="Search for users..." onkeyup="searchUsers()">

    <!-- User container -->

    <div id="userContainer">
        <?php foreach ($employees as $employee): ?>
        <div class="user-box">
            <img src="https://via.placeholder.com/50" alt="User Profile" class="profile-picture">
            <div class="user-info">
                <h2><?= htmlspecialchars($employee['first_name']) ?></h2>
                <!-- Display assigned tasks -->
                <ul>
                <?php $assignedTaskTypes = $user->getAssignedTaskTypes($employee['user_id']); ?>
                    <?php foreach ($assignedTaskTypes as $task): ?>
                        <li><?= htmlspecialchars($task['task_type_name']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <a href="user.php" class="go-back-button" type="button">Go Back</a>

</body>

</html>

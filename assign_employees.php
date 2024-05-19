<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/Location.php";

include 'logged_in.php';

include 'permission_manager.php';

$user = new User();
$employees = $user->getEmployeeUsers(); // Get the employee users



?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profiles</title>
    <link rel="stylesheet" href="styles/edit_manager.css">
    <script src="script/edit_manager.js" defer></script>
 
</head>
<body>

    <!-- Search bar -->
    <input type="text" id="searchBar" placeholder="Search for employees..." onkeyup="searchUsers()">

    <!-- User container -->

    <div id="userContainer">
        <?php foreach ($employees as $employee): ?>
        <div class="user-box">
            <img src="../LittleSun-Project/images/profile.jpg" alt="User Profile" class="profile-picture">
            <div class="user-info">
    <h2><?= htmlspecialchars($employee['first_name']) ?></h2>
    <a href="assign_work.php" class="delete-button" type="button">Assign work</a>
</div>

        
    </div>
    <?php endforeach; ?>
    </div>

    <a href="schedule_manager.php" class="go-back-button" type="button">Go Back</a>

</body>


</html>

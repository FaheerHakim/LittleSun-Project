<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/Location.php";

include 'logged_in.php';

include 'permission_admin.php';

$user = new User();
$managers = $user->getManagerUsers(); // Get the employee users



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
    <input type="text" id="searchBar" placeholder="Search for managers..." onkeyup="searchUsers()">

    <!-- User container -->

    <div id="userContainer">
        <?php foreach ($managers as $manager): ?>
        <div class="user-box">
            <img src="../LittleSun-Project/images/profile.jpg" alt="User Profile" class="profile-picture">
            <div class="user-info">
    <h2><?= htmlspecialchars($manager['first_name']) ?></h2>
    <a href="edit_password.php?user_id=<?= htmlspecialchars($manager['user_id']) ?>" class="edit-button" type="button">Edit password</a>
    <a href="" class="delete-button" type="button">Delete</a>
</div>

        
    </div>
    <?php endforeach; ?>
    </div>

    <a href="manager.php" class="go-back-button" type="button">Go Back</a>

</body>


</html>

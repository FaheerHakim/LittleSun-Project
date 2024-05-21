<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/Location.php";

include 'logged_in.php';

include 'permission_admin.php';

$user = new User();
$managers = $user->getManagerUsers();



?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profiles</title>
    <link rel="stylesheet" href="styles/edit_managers.css">
    <script src="script/edit_manager.js" defer></script>
 
</head>
<body>
    <h1>Existing managers</h1> 
    <div id="userContainer">
        <input type="text" id="searchBar" placeholder="Search for managers..." onkeyup="searchUsers()">
        <div class="form-group">
            <?php foreach ($managers as $manager): ?>
                <div class="user-box">
                    <?php
                        
                        $profilePicture = !empty($manager['profile_picture']) ? $manager['profile_picture'] : "../LittleSun-Project/images/profile.jpeg"; 
                        ?>
                        <img src="<?= htmlspecialchars($profilePicture) ?>" alt="User Profile" class="profile-picture">
                    <div class="user-info">
                        <h2><?= htmlspecialchars($manager['first_name'] . " " . $manager['last_name']) ?></h2>
                        <a href="edit_password.php?user_id=<?= htmlspecialchars($manager['user_id']) ?>" class="button edit" type="button">Edit password</a>
                        <a href="edit_manager_detail.php?user_id=<?= htmlspecialchars($manager['user_id']) ?>" class="button view" type="button">View profile</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <a href="manager.php" class="go-back-button" type="button">Go Back</a>
   
</body>


</html>

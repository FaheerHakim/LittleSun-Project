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
<style>
/* Global styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 0;
}

/* Search bar container */
.search-container {
    padding: 20px;
    text-align: center;
}

#searchBar {
    width: 400px; /* Give some margin on the sides */
    padding: 20px;
    margin-left: 100px;
    margin-top: 30px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* User container */
#userContainer {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 20px;
}

/* User box */
.user-box {
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    margin: 10px;
    width: 250px; /* Make it a bit wider */
    text-align: center;
    background-color: #ffffff;
    transition: box-shadow 0.3s; /* For hover effect */
}

.user-box:hover {
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15); /* Subtle hover effect */
}

.profile-picture {
    border-radius: 50%;
    width: 60px;
    height: 60px;
}

.user-info {
    margin-top: 15px;
}

.user-info h2 {
    font-size: 20px;
    margin: 0;
    color: #333;
}

.user-info p {
    font-size: 14px;
    color: #666;
}

.user-info ul {
    padding: 0;
    list-style: none;
}

.user-info li {
    font-size: 14px;
    color: #444;
    text-align: left; /* Align text in list items */
}

/* Edit button */
.edit-button {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 10px; /* Add padding */
    background-color: #e67e22; /* Use orange color */
    color: white;
    text-decoration: none; /* Remove underline */
    border-radius: 5px; /* Round the corners */
    transition: background-color 0.3s; /* Smooth hover effect */
}

.edit-button:hover {
    background-color: #d35400; /* Darker shade on hover */
}

/* Delete button */
.delete-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #e74c3c; /* Red color */
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.delete-button:hover {
    background-color: #c0392b; /* Darker red on hover */
}

.go-back-button {
    position: absolute;
    top: 40px;
    left: 10px;
    padding: 10px;
    background-color: #3498db; /* Blue button for 'Go Back' */
    border: none;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.go-back-button:hover {
    background-color: #2980b9;
}


</style>
<script>
    function searchUsers() {
    var input = document.getElementById('searchBar');
    var filter = input.value.toLowerCase();
    var userBoxes = document.querySelectorAll('.user-box');

    userBoxes.forEach(function(box) {
        var userName = box.querySelector('.user-info h2').textContent.toLowerCase();
        if (userName.includes(filter)) {
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
        }
    });
}

</script>
</html>

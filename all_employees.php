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

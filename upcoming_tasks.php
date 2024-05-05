<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/TaskType.php";

// Fetch existing users and task types from the database
$userHandler = new User();
$taskTypeHandler = new TaskType();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID
$loggedInUserId = $_SESSION['user']['user_id'];

// Get the assigned task types for the logged-in user
$assignedTaskTypes = $userHandler->getAssignedTaskTypes($loggedInUserId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigned tasks</title>
    <link rel="stylesheet" href="styles/upcoming_tasks.css">
</head>
<body>
<a href="dashboard.php" class="go-back-button" type="button">Go Back</a>

<div class="form-container">

    <div class="form-group">
        <h2>Assigned tasks</h2>
        <ul>
            <?php foreach ($assignedTaskTypes as $taskType): ?>
                <li><?= htmlspecialchars($taskType['task_type_name']) ?></li>
            <?php endforeach; ?>
            </ul>
    </div>
</div>   

    
</body>
</html>
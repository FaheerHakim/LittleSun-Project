<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/TaskType.php";


$userHandler = new User();
$taskTypeHandler = new TaskType();

require_once 'logged_in.php';
require_once 'permission_employee.php';


$loggedInUserId = $_SESSION['user']['user_id'];


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
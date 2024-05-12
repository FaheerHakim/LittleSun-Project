<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/TaskType.php";

// Fetch existing users and task types from the database
$userHandler = new User();
$taskTypeHandler = new TaskType();

include 'logged_in.php';

include 'permission_manager.php';

// Add task type to user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['task_type_id'])) {
    $userId = $_POST['user_id'];
    $taskTypeId = $_POST['task_type_id'];
    
    // Assign task type to user
    $userHandler->assignTaskType($userId, $taskTypeId);
}

// Remove task type assignment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_task_type']) && isset($_POST['user_id']) && isset($_POST['task_type_id'])) {
    $userId = $_POST['user_id'];
    $taskTypeId = $_POST['task_type_id'];

    // Remove task type assignment
    $success = $userHandler->removeTaskTypeAssignment($userId, $taskTypeId);

    if ($success) {
        // Assignment removed successfully
        // You can redirect the user or display a success message
    } else {
        // There was an error removing the assignment
        // You can display an error message or handle the situation accordingly
    }
}

// Get users and task types
$employeeUsers = $userHandler->getEmployeeUsers();
$taskTypes = $taskTypeHandler->getTaskTypes();


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Task Types</title>
    <link rel="stylesheet" href="styles/assign_task_types.css">
    <script src="script/assign_task_type.js" defer></script>

</head>
<body>
<div class="assign-task-container">
        <a href="dashboard.php" class="go-back-button" type="button">Go Back</a>
        <h1>Assign Task Types to Users</h1>
        <input type="text" id="searchBar" placeholder="Search for users..." onkeyup="searchUsers()">
        <div class="sub-container">
        <?php foreach ($employeeUsers as $user): ?>
            <div class="user-box">
                <p>User: <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>
                <label for="task_type">Assigned task types:</label>
                <ul>
                    <?php $assignedTaskTypes = $userHandler->getAssignedTaskTypes($user['user_id']); ?>
                    <?php foreach ($assignedTaskTypes as $assignedTaskType): ?>
                        <li>
                            <?php echo $assignedTaskType['task_type_name']; ?>
                            <form action="assign_task_types.php" method="post">
                                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                <input type="hidden" name="task_type_id" value="<?php echo $assignedTaskType['task_type_id']; ?>">
                                <button class="remove-button" type="submit" name="remove_task_type">Remove</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <form action="assign_task_types.php" method="post" onsubmit="return confirmAssignment()">
                    <label for="task_type">Add Task Type:</label>
                    <select name="task_type_id" id="task_type">
                        <?php foreach ($taskTypes as $taskType): ?>
                            <option value="<?php echo $taskType['task_type_id']; ?>"><?php echo $taskType['task_type_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                    <button class="assign-button" type="submit">Assign Task Type</button> 
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    </div>
    
</body>


</html>
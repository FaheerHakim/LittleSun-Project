<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/TaskType.php";

// Fetch existing users and task types from the database
$userHandler = new User();
$taskTypeHandler = new TaskType();

// Add task type to user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['task_type_id'])) {
    $userId = $_POST['user_id'];
    $taskTypeId = $_POST['task_type_id'];
    
    // Assign task type to user
    $userHandler->assignTaskType($userId, $taskTypeId);
}

// Get users and task types
$users = $userHandler->getUsers(); // Assuming you have a method to get users
$taskTypes = $taskTypeHandler->getTaskTypes();


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Task Types</title>
</head>
<body>
<h1>Assign Task Types to Users</h1>
    
    <form action="assign_task_types.php" method="post">
        <label for="user">Select User:</label>
        <select name="user_id" id="user">
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['user_id']; ?>"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
            <?php endforeach; ?>
        </select>
        
        <label for="task_type">Select Task Type:</label>
        <select name="task_type_id" id="task_type">
            <?php foreach ($taskTypes as $taskType): ?>
                <option value="<?php echo $taskType['task_type_id']; ?>"><?php echo $taskType['task_type_name']; ?></option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit">Assign Task Type</button>
    </form>
    
    <!-- Add additional HTML or JavaScript as needed -->
</body>
</html>
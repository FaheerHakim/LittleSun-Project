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
<div class="assign-task-containerr">

 <a href="task_types_manager.php" class="go-back-button" type="button">Go Back</a>
<h1>Assign Task Types to Users</h1>

<input type="text" id="searchBar" placeholder="Search for users..." onkeyup="searchUsers()">
    
 <?php foreach ($employeeUsers as $user): ?>
    <form action="assign_task_types.php" method="post" onsubmit="return confirmAssignment()">
       
      <div class="user-box">
        <p><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>
           

        <label for="task_type">Select Task Type:</label>
        <select name="task_type_id" id="task_type">
            <?php foreach ($taskTypes as $taskType): ?>
                <option value="<?php echo $taskType['task_type_id']; ?>"><?php echo $taskType['task_type_name']; ?></option>
            <?php endforeach; ?>
        </select>
        
        <button class="assign-button"type="submit">Assign Task Type</button> 
      </div>
    
        <?php endforeach; ?>

       
    </form>
    </div>
    
    <!-- Add additional HTML or JavaScript as needed -->
</body>


</html>
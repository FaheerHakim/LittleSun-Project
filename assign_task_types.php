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
    <script src="script/assign_task_type.js" defer></script>
</head>
<body>
<div class="form-container">
<h1>Assign Task Types to Users</h1>
    
    <form action="assign_task_types.php" method="post" onsubmit="return confirmAssignment()">
        <label for="user">Select User:</label>
        <select name="user_id" id="user">
            <?php foreach ($employeeUsers as $user): ?>
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
    </div>
    
    <!-- Add additional HTML or JavaScript as needed -->
</body>
<style>
  
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .form-container h2 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            font-weight: bold;
            margin-top: 10px;
            color: #555;
        }

        .form-container select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fafafa;
            margin-top: 5px;
            transition: border 0.2s;
        }

        .form-container select:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 15px;
        }

        .form-container button:hover {
            background-color: #45a049;
        }
    </style>

</html>
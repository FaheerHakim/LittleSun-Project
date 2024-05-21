<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/TaskType.php";

$userHandler = new User();
$taskTypeHandler = new TaskType();

include 'logged_in.php';
include 'permission_manager.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['task_type_id'])) {
    $userId = $_POST['user_id'];
    $taskTypeId = $_POST['task_type_id'];
    $userHandler->assignTaskType($userId, $taskTypeId);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_task_type']) && isset($_POST['user_id']) && isset($_POST['task_type_id'])) {
    $userId = $_POST['user_id'];
    $taskTypeId = $_POST['task_type_id'];
    $success = $userHandler->removeTaskTypeAssignment($userId, $taskTypeId);
}

$employeeUsers = $userHandler->getEmployeeUsers();
$taskTypes = $taskTypeHandler->getTaskTypes();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Task Types</title>
    <link rel="stylesheet" href="styles/assign_task_types.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="script/assign_task_type.js" defer></script>
</head>
<body>
 <h1>Assign Task Types to Users</h1>

    <div class="assign-task-container">
        <a href="dashboard.php" class="go-back-button" type="button">Go Back</a>
       
        <input type="text" id="searchBar" placeholder="Search for users..." onkeyup="searchUsers()">
        <div class="sub-container">
            <?php foreach ($employeeUsers as $user): ?>
                <div class="user-box">
                    <p><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>
                    <ul>
                        <?php $assignedTaskTypes = $userHandler->getAssignedTaskTypes($user['user_id']); ?>
                        <?php foreach ($assignedTaskTypes as $assignedTaskType): ?>
                            <li class="task-item">
                                <span><?php echo $assignedTaskType['task_type_name']; ?></span>
                                <form action="assign_task.php" method="post" class="delete-form">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <input type="hidden" name="task_type_id" value="<?php echo $assignedTaskType['task_type_id']; ?>">
                                    <button type="submit" name="remove_task_type" class="delete-button">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <form action="assign_task.php" method="post">
                        <label for="task_type">Add Task Type:</label>
                        <select name="task_type_id" id="task_type">
                            <?php 
                            $assignedTaskTypeIds = array_column($assignedTaskTypes, 'task_type_id');
                            foreach ($taskTypes as $taskType): 
                                if (!in_array($taskType['task_type_id'], $assignedTaskTypeIds)): ?>
                                    <option value="<?php echo $taskType['task_type_id']; ?>"><?php echo $taskType['task_type_name']; ?></option>
                                <?php endif; 
                                
                            endforeach; 
                            ?>
                           
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
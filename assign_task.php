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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['task_type_id']) && isset($_POST['action'])) {
    ob_start(); 
    $response = ['status' => 'failure', 'message' => ''];

    $userId = $_POST['user_id'];
    $taskTypeId = $_POST['task_type_id'];
    $action = $_POST['action'];

    try {
        if ($action === 'assign') {
            $result = $userHandler->assignTaskType($userId, $taskTypeId);
        } elseif ($action === 'remove') {
            $result = $userHandler->removeTaskTypeAssignment($userId, $taskTypeId);
        }
        if ($result) {
            $response['status'] = 'success';
        } else {
            $response['message'] = 'Database operation failed';
        }
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    ob_end_clean(); 
    echo json_encode($response);
    exit();
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
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script>
        function updateTaskType(userId, taskTypeId, action) {
            $.post('assign_task.php', { user_id: userId, task_type_id: taskTypeId, action: action }, function(response) {
                console.log('Task type update response:', response);
                try {
                    response = JSON.parse(response);
                    if(response.status === 'success') {
                        console.log('Task type successfully updated.');
                    } else {
                        console.log('Failed to update task type:', response.message);
                    }
                } catch (e) {
                    console.error('Error parsing JSON response:', e, response);
                }
            });
        }

        $(document).ready(function() {
            $('.task-type-checkbox').change(function() {
                var userId = $(this).data('user-id');
                var taskTypeId = $(this).val();
                var action = $(this).is(':checked') ? 'assign' : 'remove';
                updateTaskType(userId, taskTypeId, action);
            });
        });
    </script>
</head>
<body>
<a href="task_type_manager.php" class="go-back-button" type="button">Go Back</a>
<h1>Assign Task Types</h1>

<div id="userContainer">
    <input type="text" id="searchBar" placeholder="Search for users..." onkeyup="searchUsers()">
    <div class="form-group">
        <?php foreach ($employeeUsers as $user): ?>
            <div class="user-box">
                    <?php
                    $profilePicture = !empty($user['profile_picture']) ? $user['profile_picture'] : "../LittleSun-Project/images/profile.jpg"; 
                    ?>                    
                    <img src="<?= htmlspecialchars($profilePicture) ?>" alt="User Profile" class="profile-picture">                        
                     <div class="user-info">
                    <h2><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h2>
                    <?php 
                    $assignedTaskTypes = $userHandler->getAssignedTaskTypes($user['user_id']);
                    $assignedTaskTypeIds = array_column($assignedTaskTypes, 'task_type_id');
                    ?>
                    <div class="task-types">
                        <label class="label" for="task_types">Task Types:</label>
                        <?php foreach ($taskTypes as $taskType): ?>
                            <div class="task-types-content">
                                <input type="checkbox" class="task-type-checkbox" name="task_type_id[]" value="<?php echo $taskType['task_type_id']; ?>" 
                                    <?php echo in_array($taskType['task_type_id'], $assignedTaskTypeIds) ? 'checked' : ''; ?>
                                    data-user-id="<?php echo $user['user_id']; ?>">
                                <label><?php echo $taskType['task_type_name']; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>

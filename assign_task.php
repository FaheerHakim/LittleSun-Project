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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Task Types</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="script/assign_task_type.js" defer></script>
</head>
<body>

 <h1>Assign Task Types to Users</h1>
 

    <div class="assign-task-container">
    
       
        <input type="text" id="searchBar" placeholder="Search for users..." onkeyup="searchUsers()">
        <div class="sub-container">
            <?php foreach ($employeeUsers as $user): ?>
                <div class="user-box">
                    <p><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>
                    <label for="task_type">Assigned task types:</label>
                    <ul>
                        <?php $assignedTaskTypes = $userHandler->getAssignedTaskTypes($user['user_id']); ?>
                        <?php foreach ($assignedTaskTypes as $assignedTaskType): ?>
                            <li>
    <span><?php echo $assignedTaskType['task_type_name']; ?></span>
    <div class="delete-button-container">
        <form action="assign_task.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
            <input type="hidden" name="task_type_id" value="<?php echo $assignedTaskType['task_type_id']; ?>">
            <button type="submit" name="remove_task_type" class="delete-button">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    </div>
</li>

                        <?php endforeach; ?>
                    </ul>
                    <form action="assign_task.php" method="post" onsubmit="return confirmAssignment()">
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
              <a href="dashboard.php" class="go-back-button" type="button">Go Back</a>
        </div>
    </div>
</body>

<style>
    /* assign_task_type.css */

body {
    font-family: Arial, sans-serif;
    margin: 0;
 background-color: black;
    color: white;
}

h1 {
    text-align: center;
}

.assign-task-container {
   
    margin-top: 20px;
    min-height: 85vh; 
    padding: 20px;
    background-color: white;
    color: black;
}

.go-back-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #FFDD00;
    color: black;
    text-decoration: none;
    border: none;
    border-radius: 5px;
    margin-bottom: 20px;
    top: 20px;
    left: 15px;
    padding: 10px;
    position: absolute;
}



.sub-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.user-box {
    width: calc(50% - 20px);
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    padding: 20px;
    border-radius: 8px;
    box-sizing: border-box;
}

.user-box p {
    margin: 0 0 10px;
    font-weight: bold;
}

ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.delete-button {
   
    color: red;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    position: absolute;
    margin-left: 150px;
    margin-bottom: 20px;

   
}


.assign-button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 7px;
    position: relative;
}

.assign-button:hover {
    background-color: #0056b3;
}

#searchBar {
    width: 30%;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

.delete-button-container {
    display: inline-block; /* Ensures the container only takes up the necessary space */
    vertical-align: top; /* Adjusts the vertical alignment */
    margin-right: 10px; /* Adjusts the spacing between the task type text and the delete button */
}


</style>
</html>



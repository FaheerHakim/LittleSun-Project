<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/TaskType.php";

include 'logged_in.php';

include 'permission_admin.php';
// Add task type
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task_type']) && !empty($_POST['add_task_type'])) {
    // Validate input
    $typeName = $_POST['add_task_type']; // You may want to perform further validation
    // Add task type
    $taskTypeHandler = new TaskType();
    $taskTypeHandler->addTaskType($typeName);
    header("Location: add_task_types.php");
    exit();
}

// Delete task type
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_task_type'])) {
    $typeId = $_POST['delete_task_type'];
    // Database connection
    $taskTypeHandler = new TaskType();
    // Delete task type
    $taskTypeHandler->deleteTaskType($typeId);
    header("Location: add_task_types.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_task_type'])) {
    $typeId = $_POST['task_type_id'];
    $typeName = $_POST['task_type_name'];
    
    // Database connection
    $taskTypeHandler = new TaskType();
    
    // Update task type
    $taskTypeHandler->updateTaskType($typeId, $typeName);
    // You may need to implement the updateTaskType method in your TaskType class
    // to handle updating the task type in the database.
    
    // No need for a redirect or response here since this is an AJAX request
    exit();
}

// Get existing task types
$taskTypeHandler = new TaskType();
$taskTypes = $taskTypeHandler->getTaskTypes();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task types</title>
    <link rel="stylesheet" href="styles/task_types.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="script/task_type.js" defer></script>

</head>
<body>

<h1>Task types</h1>


<div class="form-container">
<form action="add_task_types.php" method="post" enctype="multipart/form-data">
    <div class="profile-picture" title="Profile Picture"></div>
    
     <div class="form-group-add">
         <label for="task_types">Add a new task type</label>
         <input type="text" id="add_task_type" name="add_task_type" autocomplete="off">
         <button type="submit" class="add-button">
            <i class="fa-solid fa-plus"></i>
         </button>
    </div>
</form>
<div class="line"></div>
<label for="task_types">Existing task types</label>
<div class="form-group">
        <?php foreach ($taskTypes as $taskType): ?>
            <div class="form-group-content" id="task_types">
                <input type="text" id="task_type_<?php echo $taskType['task_type_id']; ?>" name="existing_types[]" value="<?php echo $taskType['task_type_name']; ?>">
                <div class="buttons">
                    <!-- nog aanpassen-->
                    <button type="button" class="edit-button" onclick="editTaskType(<?php echo $taskType['task_type_id']; ?>)">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button type="button" class="delete-button" onclick="confirmDelete(event, <?php echo $taskTypeHandler->getTaskTypeNameById($taskType);?>)">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                <form id="delete_task_type_<?php echo $taskTypeHandler->getTaskTypeNameById($taskType); ?>" action="add_task_types.php" method="post" style="display: none;">
                    <input type="hidden" name="delete_task_type" value="<?php echo $taskTypeHandler->getTaskTypeNameById($taskType); ?>">
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<a href="task_type.php" class="go-back-button" type="button">Go back</a>

</body>
</html>
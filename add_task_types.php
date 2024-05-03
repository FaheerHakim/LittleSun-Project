<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/TaskType.php";

include 'logged_in.php';

include 'permission_admin.php';

// Add task type
if ($_SERVER["REQUEST_METHOD"] == "POST") {

if (isset($_POST['add_task_type']) && !empty($_POST['add_task_type'])) {
    // Validate input
    $typeName = $_POST['add_task_type']; // You may want to perform further validation
    // Add task type
    $taskTypeHandler = new TaskType();
    $taskTypeHandler->addTaskType($typeName);
}

// Delete task type
if (isset($_POST['delete_task_type'])) {
    $typeId = $_POST['delete_task_type'];
    // Delete task type
    $taskTypeHandler = new TaskType();
    $taskTypeHandler->deleteTaskType($typeId);
}
}

// Get existing task types
$taskTypeHandler = new TaskType();
$taskTypes = $taskTypeHandler->getTaskTypes();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add task types</title>
    <link rel="stylesheet" href="styles/task_types.css">
    <script src="script/task_type.js" defer></script>
</head>
<body>

<h1>Add a task type</h1>


<div class="form-container">
<form action="add_task_types.php" method="post" enctype="multipart/form-data">
    <div class="profile-picture" title="Profile Picture"></div>
    
     <div class="form-group-add">
         <label for="task_types">Task types</label>
         <input type="text" id="add_task_type" name="add_task_type" autocomplete="off">
         <button type="submit" class="add-button">Add task types</button>
    </div>
</form>

<div class="form-group">
    <label for="task_types">Existing task types</label>
    <div class="form-group-content" id="task_types">
        <ul>
            <?php foreach ($taskTypes as $taskType): ?>
                <li>
                    <?php echo $taskType['task_type_name']; ?>
                    <form id="delete_form_<?php echo $taskType['task_type_id']; ?>" action="add_task_types.php" method="post" style="display: inline;">
                        <input type="hidden" name="delete_task_type" value="<?php echo $taskType['task_type_id']; ?>">
                        <button type="submit" class="delete-button" onclick="confirmDelete(<?php echo $taskType['task_type_id']; ?>)">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
</form>
</div>

<a href="task_types_admin.php" class="go-back-button" type="button">Go Back</a>

</body>
</html>

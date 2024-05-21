<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/TaskType.php";

include 'logged_in.php';

include 'permission_admin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task_type']) && !empty($_POST['add_task_type'])) {

    $typeName = $_POST['add_task_type']; 
 
    $taskTypeHandler = new TaskType();
    $taskTypeHandler->addTaskType($typeName);
    $_SESSION['message'] = "Location added successfully.";
    $_SESSION['message_type'] = "success";
    header("Location: message-tasktype.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_task_type'])) {
    $typeId = $_POST['delete_task_type'];

    $taskTypeHandler = new TaskType();

    $taskTypeHandler->deleteTaskType($typeId);
 
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_task_type'])) {
    $typeId = $_POST['task_type_id'];
    $typeName = $_POST['task_type_name'];
    

    $taskTypeHandler = new TaskType();
    
 
    $taskTypeHandler->updateTaskType($typeId, $typeName);
    
    exit();
}


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
                <button type="button" class="edit-button" onclick="confirmEdit(<?php echo $taskType['task_type_id']; ?>)">
                    <i class="fa-solid fa-pen"></i>
                </button>
                <button type="button" class="delete-button" onclick="confirmDelete(<?php echo $taskType['task_type_id']; ?>)">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div id="editConfirmationModal" class="modal">
    <div class="modal-content">
        <p>Are you sure you want to edit this task type?</p>
        <div class="button-container">
            <button class="button no" onclick="closeEditModal()">No</button>
            <button class="button yes" id="confirmEditButton" onclick="performEdit()">Yes</button>
        </div>
    </div>
</div>
<a href="task_type.php" class="go-back-button" type="button">Go back</a>

</body>
</html>
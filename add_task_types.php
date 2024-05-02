<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add task types</title>
    <link rel="stylesheet" href="styles/task_types.css">
    <script src="script/task_types.js"></script>
</head>
<body>

<h1>Add a task type</h1>

<form action="add_task_types.php" method="post" enctype="multipart/form-data">
    <div class="profile-picture" title="Profile Picture"></div>
    
     <div class="form-group-add">
         <label for="task_types">Task types</label>
         <input type="text" id="new_task_types" name="new_task_types" autocomplete="off">
         <button type="submit" onclick="validateAndSubmit(event)" class="add-button">Add task types</button>
    </div>

    
    <div class="form-group">
        <label for="task_types">Existing task types</label>
     <div class="form-group-content" id="task_types">
    <button type="button" class="delete-button">delete task types</button>
</div>
</form>
    
    <a href="dashboard.php" class="go-back-button" type="button">Go Back</a>


</body>
</html>
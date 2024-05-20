<?php
session_start();
require_once __DIR__ . "/classes/TaskType.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_task_type'])) {
    $taskTypeId = $_GET['delete_task_type'];
} else {
    header("Location: add_task_types.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Deletion</title>
    <style>
        body{
            background-color: #f2F2F2;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .message-container {
            background-color: white;
            padding: 20px;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            text-align: center;
            min-width: 400px;
            min-height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        p.message-container{
            margin: 16px;
            font-size: 18px;
            font-weight: bold;
            
        }
        button, a{
            margin-top: 24px;
            padding: 10px 20px;
            text-decoration: none;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            background-color: #e74c3c;
        }
        button:hover{
            background-color: #c0392b;
        }
        .button-container{
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 16px;
        }
        a{
            background-color: lightgray;
        }
        
    </style>
</head>
<body>
<div class="message-container">
<p>Are you sure you want to delete this task type?</p>
    <div class="button-container">
        <a href="add_task_types.php">Cancel</a>
        <form action="add_task_types.php" method="post">
            <input type="hidden" name="delete_task_type" value="<?php echo htmlspecialchars($taskTypeId); ?>">
            <button class="button yes" type="submit">Yes, delete it</button>
        </form>
    </div>
</div>
</body>
</html>

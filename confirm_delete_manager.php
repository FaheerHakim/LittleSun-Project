<?php
session_start();

require_once __DIR__ . "/classes/User.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_manager'])) {
    $user_id = $_POST['delete_manager'];

    $user = new User();
    $user->deleteUser($user_id);

    // Redirect to the edit_manager.php page after deletion
    header("Location: edit_manager.php");
    exit();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
} else {
    // Handle the case where user ID is not provided
    header("Location: edit_manager.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Deletion</title>
    <style>
        body {
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
        p.message-container {
            margin: 16px;
            font-size: 18px;
            font-weight: bold;
        }
        button, a {
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
        button:hover {
            background-color: #c0392b;
        }
        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 16px;
        }
        a {
            background-color: lightgray;
        }
    </style>
</head>
<body>
<div class="message-container">
    <p>Are you sure you want to delete this manager?</p>
    <div class="button-container">
        <a href="edit_manager_detail.php?user_id=<?php echo htmlspecialchars($user_id); ?>">Cancel</a>
        <form action="" method="post">
            <input type="hidden" name="delete_manager" value="<?php echo htmlspecialchars($user_id); ?>">
            <button class="button yes" type="submit">Yes, delete</button>
        </form>
    </div>
</div>
</body>
</html>

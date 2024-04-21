<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// Display different content based on user type
function displayDashboard($user) {
    switch ($user['typeUser']) {
        case 'admin':
            echo "Welcome Admin! You have access to admin functionalities.";
            break;
        case 'manager':
            echo "Welcome Manager! You have access to manager functionalities.";
            break;
        case 'employee':
            echo "Welcome Employee! You have access to employee functionalities.";
            break;
        default:
            echo "Unknown user type.";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>
    <?php displayDashboard($user); ?>
    <br>
    <a href="logout.php">Logout</a>
    <a href="add_manager.php">Add manager</a>
    <a href="edit_manager.php">Edit manager profile</a>
</body>
</html>

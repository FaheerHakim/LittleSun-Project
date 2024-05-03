<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Check if user is a manager
if ($_SESSION['user']['type_user'] !== 'manager') {
    // Redirect or display an error message
    echo "You do not have permission to assign task types.";
    exit;
}

$user = $_SESSION['user'];

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>
<!-- navigation -->    
<?php include 'navigation.php'; ?>

<!-- dashboard -->    

<div class="main">
    <h1><span class="title"> Assign task types</span></h1>
    <div onclick="location.href='assign_task_types.php';" class="info-square">Assign task types</div>
</div>
</body>
</html>
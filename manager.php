<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
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

    <!-- Admin navigation -->
    <?php if($user['typeUser'] != 'manager' && $user['typeUser'] != 'employee'): ?>
    <div class="sidebar">
        <a href="#"><i id="title"></i> Little Sun</a>
        <a href="dashboard.php">Dashboard</a>
        <a >Hub Locations</a>
        <a>Hub Managers</a>
        <a>Task types</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>

    <!-- Managers navigation -->
    <?php if($user['typeUser'] != 'admin' && $user['typeUser'] != 'employee'): ?>
    <div class="sidebar">
        <a href="#"><i id="title"></i> Little Sun</a>
        <a href="dashboard.php">Dashboard</a>
        <a>Hub Locations</a>
        <a>Hub Managers</a>
        <a>Task types</a>
        <a>Work schedule</a>
        <a>Reports</a>
        <a>Time-off requests</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>

    <?php if($user['typeUser'] != 'admin' && $user['typeUser'] != 'manager'): ?>
    <!-- Employees navigation -->
    <div class="sidebar">
        <a href="#"><i id="title"></i> Little Sun</a>
        <a href="dashboard.php">Dashboard</a>
        <a>Time-off requests</a>
        <a>Sick leave</a>
        <a>Clock in/out</a>
        <a>Work schedule</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>

<!-- dashboard -->    

<div class="main">
    <h1><span class="title"> Dashboard <?php echo $user['typeUser'] ;?></span></h1>
    <div onclick="location.href='add_manager.php';" class="info-square">Add manager</div>
    <div onclick="location.href='edit_manager.php';" class="info-square">Existing managers</div>
</div>
</body>
</html>
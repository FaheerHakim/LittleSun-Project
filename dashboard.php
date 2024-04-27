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
        <a>Dashboard</a>
        <a>Hub Locations</a>
        <a>Hub Managers</a>
        <a>Task types</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>

    <!-- Managers navigation -->
    <?php if($user['typeUser'] != 'admin' && $user['typeUser'] != 'employee'): ?>
    <div class="sidebar">
        <a href="#"><i id="title"></i> Little Sun</a>
        <a>Dashboard</a>
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
        <a>Dashboard</a>
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

    <div class="info-square">Total Users</div>
    <div class="info-square">New Messages</div>
    <div class="info-square">Active Managers</div>
    <div class="info-square">Pending Tasks</div>
    <div class="info-square">Requests</div>
</div>
    <h2> Locations</h2>

    <div class="edit-locations-style">
        <?php if($user['typeUser'] != 'manager' && $user['typeUser'] != 'employee'): ?>
        <a href="add_location.php" class="btn">Location</a>
        <?php endif; ?>
        
    </div>
</body>
</html>
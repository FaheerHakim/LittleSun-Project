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
    <title>Location</title>
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
        <a href="location.php">Hub Locations</a>
        <a href="manager.php"> Hub Managers</a>
        <a>Task types</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>

    <!-- Managers navigation -->
    <?php if($user['typeUser'] != 'admin' && $user['typeUser'] != 'employee'): ?>
    <div class="sidebar">
        <a href="#"><i id="title"></i> Little Sun</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="location.php">Hub Locations</a>
        <a href="manager.php">Hub Managers</a>
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
    <h1><span class="title"> Hub locations </span></h1>
    <div onclick="location.href='add_location.php';" class="info-square">Add location</div>
    <div onclick="location.href='edit_location.php';" class="info-square">Existing locations</div>
</div>
</body>
</html>
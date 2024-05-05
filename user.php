<?php
session_start();

include 'logged_in.php';

include 'permission_manager.php';

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
    <h1><span class="title">Employees</span></h1>
    <div onclick="location.href='add_employee.php';" class="info-square">Add Employee</div>
    <div onclick="location.href='all_employees.php';" class="info-square">Existing Employees</div>
</div>
</body>
</html>
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
    <h1><span class="title"> Time off requests</span></h1>
    <div onclick="location.href='manage_time_off.php';" class="info-square">Manage time off</div>
</div>
</body>
</html>
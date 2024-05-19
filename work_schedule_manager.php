<?php
session_start();

include 'logged_in.php';

include 'permission_manager.php';

$user = $_SESSION['user'];


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Work schedule</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>
<!-- navigation -->    
<?php include 'navigation.php'; ?>

<!-- dashboard -->    

<div class="main">
    <h1><span class="title"> Work schedule</span></h1>
    <div onclick="location.href='assign_work_schedule.php';" class="info-square">Assign work schedule</div>
    <div onclick="location.href='schedule_manager.php';" class="info-square">Overview work schedule</div>
</div>
</body>
</html>
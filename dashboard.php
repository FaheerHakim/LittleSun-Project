<?php
session_start();

include 'logged_in.php';

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
    <h1><span class="title"> Dashboard <?php echo $user['type_user'] ;?></span></h1>
    
    
    <div class="info-square">Total hours worked</div>
    <div onclick="location.href='upcoming_tasks.php';" class="info-square">Upcoming tasks</div>
    <div class="info-square">Time-off requests</div>
   

</div>
</body>
</html>
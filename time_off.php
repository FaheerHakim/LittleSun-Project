<?php
session_start();

include 'logged_in.php';

include 'permission_employee.php';

$user = $_SESSION['user'];

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request time-off</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>
  
<?php include 'navigation.php'; ?>


<div class="main">
    <h1><span class="title"> Request time-off</span></h1>
    <div onclick="location.href='request_time_off.php';" class="info-square">Request time off</div>
</div>
</body>
</html>
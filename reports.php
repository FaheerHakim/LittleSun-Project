<?php
session_start();

include 'logged_in.php';

include 'permission_manager.php';

$user = $_SESSION['user'];

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>
  
<?php include 'navigation.php'; ?>



<div class="main">
    <h1><span class="title">Reports</span></h1>
    <div onclick="location.href='report.php';" class="info-square">Generate report</div>
</div>
</body>
</html>
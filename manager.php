<?php
session_start();

include 'logged_in.php';

include 'permission_admin.php';

$user = $_SESSION['user'];

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hub managers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>
  
<?php include 'navigation.php'; ?>



<div class="main">
    <h1><span class="title"> Hub managers</span></h1>
    <div onclick="location.href='add_manager.php';" class="info-square">Add manager</div>
    <div onclick="location.href='edit_manager.php';" class="info-square">Existing managers</div>
</div>
</body>
</html>
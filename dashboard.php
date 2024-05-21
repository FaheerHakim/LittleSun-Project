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

<?php include 'navigation.php'; ?>

  

<div class="main">
    <h1><span class="title"> Dashboard </span></h1>
    
    
    <?php if($user['type_user'] != 'manager' && $user['type_user'] != 'employee'): ?>  
        <h2>Quick actions</h2>
        <div onclick="location.href='add_location.php';" class="info-square">Add & edit locations</div>
        <div onclick="location.href='add_manager.php';" class="info-square">Add manager</div>
        <div onclick="location.href='add_task_types.php';" class="info-square">Add & edit task types</div>
    <?php endif; ?>
        

  
     <?php if($user['type_user'] != 'admin' && $user['type_user'] != 'employee'): ?>
        <h2>Quick actions</h2>
         <div onclick="location.href='add_employee.php';" class="info-square">Add Employee</div>
        <div onclick="location.href='assign_task.php';" class="info-square">Assign task types</div>
        <div onclick="location.href='manage_time_off.php';" class="info-square">Manage time off</div>
         <div onclick="location.href='schedule_manager.php';" class="info-square">Overview work schedule</div>
    <?php endif; ?>

    <?php if($user['type_user'] != 'admin' && $user['type_user'] != 'manager'): ?>
        <h2>Quick actions</h2>
        <div onclick="location.href='clock_in_out.php';" class="info-square">Clock in & out</div>
        <div onclick="location.href='request_time_off.php';" class="info-square">Request time off</div>
         <div onclick="location.href='schedule_employee.php';" class="info-square">Work schedule</div>
    <?php endif; ?>
    
   

</div>
</body>
</html>
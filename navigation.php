

<?php 

    require_once __DIR__ . "/classes/User.php";

    $loggedInUser = $user; 
    $userObj = new User();
    $userDetails = $userObj->getUserById($loggedInUser['user_id']);
?>
   
    <?php if($user['type_user'] != 'manager' && $user['type_user'] != 'employee'): ?>  
    <div class="sidebar">
    <img src="images/little-sun-logo-3895446415.png" alt="Little Sun Logo" style="max-width: 135px; max-height: 135px; margin-left: 30px; margin-top: 30px; margin-bottom: 20px;">
        <a href="dashboard.php">Dashboard</a>
        <a href="location.php"> Hub Locations</a>
        <a href="manager.php"> Hub Managers</a>
        <a href="task_type.php"> Task Types</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>

    <?php if($user['type_user'] != 'admin' && $user['type_user'] != 'employee'): ?>
    <div class="sidebar">
  <img src="images/little-sun-logo-3895446415.png" alt="Little Sun Logo" style="max-width: 135px; max-height: 135px; margin-left: 30px; margin-top: 30px; margin-bottom: 20px;">
        <a href="dashboard.php">Dashboard</a>
        <a href="user.php">Employees</a>
        <a href="task_type_manager.php"> Task Types</a>
        <a href="work_schedule_manager.php">Work schedule</a>
        <a href="reports.php">Reports</a>
        <a href="time_off_requests.php"> Time-Off requests</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>

    <?php if($user['type_user'] != 'admin' && $user['type_user'] != 'manager'): ?>
  
    <div class="sidebar">
    <img src="images/little-sun-logo-3895446415.png" alt="Little Sun Logo" style="max-width: 135px; max-height: 135px; margin-left: 30px; margin-top: 30px; margin-bottom: 20px;">
        <a href="dashboard.php">Dashboard</a>
        <a href="work_schedule_employee.php">Work schedule</a>
        <a href="clock_in_out_employee.php">Clock in/out</a>
        <a href="time_off.php"> Request time-off</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>
    <?php
    $profilePicture = !empty($userDetails['profile_picture']) ? $userDetails['profile_picture'] : './images/profile.jpg';
    ?>
 
    <div class="user-profile">
    <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture">
    <div class="user-details">
        <span class="name"><?php echo $userDetails['first_name'] . ' ' . $userDetails['last_name']; ?></span>
        <span class="user-type"><?php echo ucfirst($userDetails['type_user']); ?></span>
    </div>
</div> 
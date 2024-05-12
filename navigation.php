<!-- navigation -->   

<?php 

    require_once __DIR__ . "/classes/User.php";

    $loggedInUser = $user; // Assuming $user contains the logged-in user's details
    $userObj = new User();
    $userDetails = $userObj->getUserById($loggedInUser['user_id']);
?>
    <!-- Admin navigation (niet manager en niet employee) -->
    <?php if($user['type_user'] != 'manager' && $user['type_user'] != 'employee'): ?>  
    <div class="sidebar">
        <a href="#"><i id="title"><img src="images/little-sun-logo-3895446415.png" alt="Little Sun Logo"></i></a>
        <a href="dashboard.php">Dashboard</a>
        <a href="location.php">Hub Locations</a>
        <a href="manager.php"> Hub Managers</a>
        <a href="task_types_admin.php">Task types</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>

    <!-- Managers navigation (niet admin en niet employee)-->
    <?php if($user['type_user'] != 'admin' && $user['type_user'] != 'employee'): ?>
    <div class="sidebar">
        <a href="#"><i id="title"><img src="images/little-sun-logo-3895446415.png" alt="Little Sun Logo"></i></a>
        <a href="dashboard.php">Dashboard</a>
        <a href="user.php">Employees</a>
        <a href="task_types_manager.php">Task types</a>
        <a href="work_schedule_manager.php">Work schedule</a>
        <a>Reports</a>
        <a href="time_off_request_manager.php">Time-off requests</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>

    <?php if($user['type_user'] != 'admin' && $user['type_user'] != 'manager'): ?>
    <!-- Employees navigation (niet admin en niet manager) -->
    <div class="sidebar">
    <a href="#"><i id="title"><img src="images/little-sun-logo-3895446415.png" alt="Little Sun Logo"></i></a>
        <a href="dashboard.php">Dashboard</a>
        <a href="time_off_request_employee.php">Time-off requests</a>
        <a>Sick leave</a>
        <a href="clock_in_out.php">Clock in/out</a>
        <a href="work_schedule.php">Work schedule</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>

    <!-- User Profile Section -->
    <div class="user-profile">
    <img src="<?php echo $userDetails['profile_picture']; ?>" alt="Profile Picture"> 
    <div class="user-details">
        <span class="name"><?php echo $userDetails['first_name'] . ' ' . $userDetails['last_name']; ?></span>
        <span class="user-type"><?php echo ucfirst($userDetails['type_user']); ?></span>
    </div>
</div> 
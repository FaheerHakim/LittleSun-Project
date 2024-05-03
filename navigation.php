<!-- navigation -->    

    <!-- Admin navigation -->
    <?php if($user['type_user'] != 'manager' && $user['type_user'] != 'employee'): ?>
    <div class="sidebar">
        <a href="#"><i id="title"></i> Little Sun</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="location.php">Hub Locations</a>
        <a href="manager.php"> Hub Managers</a>
        <a href="task_types_admin.php">Task types</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>

    <!-- Managers navigation -->
    <?php if($user['type_user'] != 'admin' && $user['type_user'] != 'employee'): ?>
    <div class="sidebar">
        <a href="#"><i id="title"></i> Little Sun</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="task_types_manager.php">Task types</a>
        <a>Work schedule</a>
        <a>Reports</a>
        <a href="time_off_request_manager.php">Time-off requests</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>

    <?php if($user['type_user'] != 'admin' && $user['type_user'] != 'manager'): ?>
    <!-- Employees navigation -->
    <div class="sidebar">
        <a href="#"><i id="title"></i> Little Sun</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="time_off_request_employee.php">Time-off requests</a>
        <a>Sick leave</a>
        <a>Clock in/out</a>
        <a>Work schedule</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>
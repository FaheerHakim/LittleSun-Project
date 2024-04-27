<!-- navigation -->    

    <!-- Admin navigation -->
    <?php if($user['typeUser'] != 'manager' && $user['typeUser'] != 'employee'): ?>
    <div class="sidebar">
        <a href="#"><i id="title"></i> Little Sun</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="location.php">Hub Locations</a>
        <a href="manager.php"> Hub Managers</a>
        <a>Task types</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>

    <!-- Managers navigation -->
    <?php if($user['typeUser'] != 'admin' && $user['typeUser'] != 'employee'): ?>
    <div class="sidebar">
        <a href="#"><i id="title"></i> Little Sun</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="location.php">Hub Locations</a>
        <a href="manager.php">Hub Managers</a>
        <a>Task types</a>
        <a>Work schedule</a>
        <a>Reports</a>
        <a>Time-off requests</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>

    <?php if($user['typeUser'] != 'admin' && $user['typeUser'] != 'manager'): ?>
    <!-- Employees navigation -->
    <div class="sidebar">
        <a href="#"><i id="title"></i> Little Sun</a>
        <a href="dashboard.php">Dashboard</a>
        <a>Time-off requests</a>
        <a>Sick leave</a>
        <a>Clock in/out</a>
        <a>Work schedule</a>
        <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>
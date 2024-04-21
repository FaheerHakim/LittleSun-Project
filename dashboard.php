<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// Display different content based on user type
function displayDashboard($user) {
    switch ($user['typeUser']) {
        case 'admin':
            echo "Welcome Admin! You have access to admin functionalities.";
            break;
        case 'manager':
            echo "Welcome Manager! You have access to manager functionalities.";
            break;
        case 'employee':
            echo "Welcome Employee! You have access to employee functionalities.";
            break;
        default:
            echo "Unknown user type.";
            break;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Optional for icons -->
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

 
        .sidebar {
            height: 100vh;
            width: 200px;
            position: fixed;
            background-color: #333;
            padding-top: 40px;
            color: white;
        }

        .sidebar a {
            display: block;
            padding: 40px;
            color: white;
            text-decoration: none;
        }

       

        .sidebar a:hover {
            background-color: #555;
        }
    
        .main {
            margin-left: 200px;
            padding: 20px;
        }

        .info-square {
            display: inline-block;
            width: 200px;
            height: 150px;
            background-color: #3498db;
            color: white;
            text-align: center;
            line-height: 150px; /* Center text vertically */
            border-radius: 10px;
            margin: 10px;
        }


    

        .btn {
            padding: 10px 40px; /* Padding for buttons */
            margin-left: 240px;
            background-color: #3498db; /* Blue background */
            color: white; /* White text color */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            text-decoration: none; /* Remove underline from links */
            font-size: 16px; /* Font size */
            transition: background-color 0.3s; /* Smooth transition on hover */
            cursor: pointer; /* Change cursor on hover */
        }

        /* Hover effect for buttons */
        .btn:hover {
            background-color: #2980b9; /* Darker blue on hover */
        }

    </style>
</head>
<body>

<div class="sidebar">
    <a href="#"><i id="title"></i> Little Sun</a>
    <a href="#"><i class="fas fa-tachometer-alt"></i><span class="title"> Dashboard <?php echo $user['typeUser'] ;?></span></a>
    <?php if($user['typeUser'] != 'manager' && $user['typeUser'] != 'employee'): ?>
    <a href="add_manager.php"><i class="fas fa-user-plus"></i> Add Managers</a>
<?php endif; ?>
    <a href="edit_manager.php"><i class="fas fa-key"></i> Reset Passwords</a>
    <a href="#"><i class="fas fa-users"></i> Employees</a>
    <a href="#"><i class="fas fa-envelope"></i> Messages</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>


<!--<?php displayDashboard($user); ?> -->

<div class="main">
    <h1><span class="title"> Dashboard <?php echo $user['typeUser'] ;?></span></h1>

    <div class="info-square">Total Users</div>
    <div class="info-square">New Messages</div>
    <div class="info-square">Active Managers</div>
    <div class="info-square">Pending Tasks</div>
    <div class="info-square">Requests</div>
    
    
    
</div>

         

            


   
                        <h2> Locations</h2>
                    
                        <div class="edit-locations-style">
                            <?php if($user['typeUser'] != 'manager' && $user['typeUser'] != 'employee'): ?>
                            <a href="add_location.php" class="btn">Location</a>
                            <?php endif; ?>
                           
                        </div>
                    </div>
                    
                    

</body>
</html>


<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'logged_in.php';

include 'permission_admin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['location'] )) {
   
        $email = $_POST['email'];
        $password = $_POST['password']; 
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $location_name = $_POST['location']; 
        $profile_picture = null;
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
            $profile_picture = $_FILES['profile_picture'];
        }

        require_once __DIR__ . "/classes/User.php";
        require_once __DIR__ . "/classes/Location.php"; 
        $userHandler = new User();
        $locationHandler = new Location();

        
        $location_id = $locationHandler->getLocationIdByName($location_name);

        if ($location_id) {
          
            $result = $userHandler->addManager($email, $password, $first_name, $last_name, $location_id, $profile_picture);

            if ($result === true) {
                
                $_SESSION['message'] = "Manager added successfully.";
                $_SESSION['message_type'] = "success";
            } elseif ($result === "Email already exists") {
            
                $_SESSION['message'] = "Email already exists.";
                $_SESSION['message_type'] = "error";
            } else {
           
                $_SESSION['message'] = "Error adding manager.";
                $_SESSION['message_type'] = "error";
            }
        } else {
         
            $_SESSION['message'] = "Location not found.";
            $_SESSION['message_type'] = "error";
        }

   
        header('Location: message.php');
        exit();
    } else {
      
        $_SESSION['message'] = "All fields are required.";
        $_SESSION['message_type'] = "error";

    
        header('Location: message.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/manager.css">
    <title>Add Manager</title>
</head>
<body>

<h1>Add Manager</h1>


<form action="add_manager.php" method="post" enctype="multipart/form-data">    
    <div class="form-container">
        <div class="form-group">
            <label for="first-name">First Name</label>
            <input type="text" id="first-name" name="first_name" required>
        </div>
        
        <div class="form-group">
            <label for="last-name">Last Name</label>
            <input type="text" id="last-name" name="last_name" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
    
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
    
    <div class="form-group">
        <label for="location">Location</label>
        <select id="location" name="location" required>
            <option value="">Select location</option>
            <?php
         
            require_once __DIR__ . "/classes/Location.php";
            $locationHandler = new Location();
            $existingLocations = $locationHandler->getExistingLocations();

       
            foreach ($existingLocations as $location) {
                echo "<option value=\"$location\">$location</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="profile-picture">Profile Picture</label>
        <input type="file" id="profile-picture" name="profile_picture" accept="image/*">
    </div>

    <button type="submit" class="add-button">Add Manager</button>

    <a href="manager.php" class="go-back-button" type="button">Go Back</a>
</form>
</div>
</body>
</html>

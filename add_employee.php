<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'logged_in.php';

include 'permission_manager.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are filled
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['location'])) {
        // Sanitize input data
        $email = $_POST['email'];
        $password = $_POST['password']; // You may want to hash the password for security
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $location_name = $_POST['location']; // Assuming location name is provided from the form
        $profile_picture = $_FILES ['profile_picture'];

        // Database connection
        require_once __DIR__ . "/classes/User.php"; // Assuming the User class exists
        require_once __DIR__ . "/classes/Location.php"; // Assuming the Location class exists
        $userHandler = new User();
        $locationHandler = new Location();

        // Get the location_id based on the location name
        $location_id = $locationHandler->getLocationIdByName($location_name);

        if ($location_id) {
            // Add the manager to the database
            $result = $userHandler->addEmployee($email, $password, $first_name, $last_name, $location_id, $profile_picture);

            if ($result) {
                // Manager added successfully
                echo "Employee added successfully.";
            } else {
                // Error adding manager
                echo "Error adding employee.";
            }
        } else {
            // Location not found
            echo "Location not found.";
        }
    } else {
        // Required fields are missing
        echo "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/manager.css">
    <title>Add Employee</title>
</head>
<body>

<h1>Add Employee</h1>

<form action="add_employee.php" method="post" enctype="multipart/form-data">    
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>
    
    <div class="form-group">
        <label for="first-name">First Name</label>
        <input type="text" id="first-name" name="first_name" required>
    </div>

    <div class="form-group">
        <label for="last-name">Last Name</label>
        <input type="text" id="last-name" name="last_name" required>
    </div>
    
    <div class="form-group">
        <label for="location">Location</label>
        <select id="location" name="location" required>
            <option value="">Select location</option>
            <?php
            // Include Location class and get existing locations
            require_once __DIR__ . "/classes/Location.php";
            $locationHandler = new Location();
            $existingLocations = $locationHandler->getExistingLocations();

            // Loop through existing locations to populate options
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

    <button type="submit" class="add-button">Add Employee</button>

    <a href="user.php" class="go-back-button" type="button">Go Back</a>
</form>

</body>
</html>

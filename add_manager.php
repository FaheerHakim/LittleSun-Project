<?php
session_start();
require_once __DIR__ . "/classes/Manager.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data

    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Secure password hashing
    $first_name = htmlspecialchars($_POST["first_name"]);
    $last_name = htmlspecialchars($_POST["last_name"]);
    $location = htmlspecialchars($_POST["location"]);

    // Handle profile picture upload
    $profile_picture = $_FILES["profile_picture"];
    $upload_dir = "uploads/";
    $upload_file = $upload_dir . basename($profile_picture["name"]);

    // Move uploaded file to the desired directory
    move_uploaded_file($profile_picture["tmp_name"], $upload_file);

    // Insert data into a database or process it further
    // Database insertion logic (e.g., MySQL) could go here

    $manager = new Manager();
    $add_manager = $manager->add_manager($email, $password, $first_name, $last_name, $location);
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
    <div class="profile-picture" title="Profile Picture"></div>
    
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
        <label for="profile-picture">Upload Profile Picture</label>
        <input type="file" id="profile-picture" name="profile_picture" accept="image/*">
    </div>

    <button type="submit" class="add-button">Add Manager</button>

    <a href="manager.php" class="go-back-button" type="button">Go Back</a>
</form>

</body>
</html>

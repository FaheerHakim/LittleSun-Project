<?php
var_dump($_POST);
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/Location.php";

include 'logged_in.php';

include 'permission_admin.php';

if (!isset($_GET['user_id'])) {
  
    header("Location: edit_manager.php");
    exit();
}

$user_id = $_GET['user_id'];
$user = new User();
$managerInfo = $user->getUserById($user_id);

$locationName = "Location does not exist"; 
$locationHandler = new Location();
$locationInfo = $locationHandler->getLocationById($managerInfo['location_id']);

if ($locationInfo) {
    $locationName = $locationInfo['city'];
}

$profilePicture = "path/to/default_profile_picture.jpeg"; 
if (!empty($managerInfo['profile_picture'])) {
    $profilePicture = $managerInfo['profile_picture'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user->updateUser($user_id, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['location']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/edit_manager_detail.css">
    <title>Edit Manager</title>
</head>
<body>

<h1>Edit Manager</h1>

<div class="form">
<form action="edit_manager_detail.php?user_id=<?= $user_id ?>" method="post">
<div class="form-container">
    <div class="form-group">
        <img src="<?= htmlspecialchars($profilePicture) ?>" alt="Profile Picture" readonly>
    </div>
    <div class="form-group">
        <label for="first-name">First Name</label>
        <input type="text" id="first-name" name="first_name" value="<?= htmlspecialchars($managerInfo['first_name']) ?>" >
    </div>
    
    <div class="form-group">
        <label for="last-name">Last Name</label>
        <input type="text" id="last-name" name="last_name" value="<?= htmlspecialchars($managerInfo['last_name']) ?>" >
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($managerInfo['email']) ?>" >
    </div>
    <div class="form-group">
        <label for="location">Location</label>
        <select id="location" name="location">
            <option value=""></option>
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

    <button type="submit" class="edit-button">Edit</button>
</div>
</form>

    <a href="manager.php" class="go-back-button" type="button">Go Back</a>
</div>
</body>
</html>

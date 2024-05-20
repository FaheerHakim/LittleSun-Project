<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/Location.php";

include 'logged_in.php';

include 'permission_admin.php';

// Add location
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_location']) && !empty($_POST['new_location'])) {
    // Validate input
    $locationName = $_POST['new_location'];
    
    // Database connection
    $locationHandler = new Location();
    
    // Add location
    $locationHandler->addLocation($locationName);
    $_SESSION['message'] = "Location added successfully.";
    $_SESSION['message_type'] = "success";
    header("Location: message-location.php");
    exit();
}

// Delete location
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_location'])) {
    $locationId = $_POST['delete_location'];
    
    // Prompt for confirmation
    if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] === 'yes') {
        // Database connection
        $locationHandler = new Location();
        
        // Delete location
        $locationHandler->deleteLocation($locationId);
        $_SESSION['message'] = "Location deleted successfully.";
        $_SESSION['message_type'] = "success";
        header("Location: message-location.php");
        exit();
    } else {
        $_SESSION['message'] = "Location deletion canceled.";
        $_SESSION['message_type'] = "error";
        header("Location: message-location.php");
        exit();
    }
}

// Update location
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_location'])) {
    $locationId = $_POST['location_id'];
    $locationName = $_POST['location_name'];
    
    // Database connection
    $locationHandler = new Location();
    
    // Update location
    $locationHandler->updateLocation($locationId, $locationName);
    // You need to implement the updateLocation method in your Location class
    // to handle updating the location in the database.
    
    // No need for a redirect or response here since this is an AJAX request
    exit();
}
// Get existing locations
$locationHandler = new Location();
$existingLocations = $locationHandler->getExistingLocations();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hub locations</title>
    <link rel="stylesheet" href="styles/location.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="script/location.js" defer></script>
</head>
<body>

<h1>Hub locations</h1>
<div class="form-container">
<form action="add_location.php" method="post" enctype="multipart/form-data">
    <div class="profile-picture" title="Profile Picture"></div>
    
    <div class="form-group-add">
        <label for="location">Add a new hub location</label>
        <input type="text" id="new_location" name="new_location" autocomplete="off">
        <button type="submit" class="add-button">
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>
    </form>
    <div class="line"></div>

    <label for="location">Hub locations</label>
    <div class="form-group">
    <?php foreach ($existingLocations as $location): ?>
        <div class="form-group-content">
        <input type="text" id="locationInput_<?php echo $locationHandler->getLocationIdByName($location); ?>" name="existing_location[]" value="<?php echo $location; ?>">
        <div class="buttons">
        <button type="button" class="edit-button" onclick="editLocation(<?php echo $locationHandler->getLocationIdByName($location); ?>)">
            <i class="fa-solid fa-pen"></i>
            </button>
            <button type="button" class="delete-button" onclick="confirmDelete(event, <?php echo $locationHandler->getLocationIdByName($location); ?>)">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>        
            <form id="delete_location_<?php echo $locationHandler->getLocationIdByName($location); ?>" action="add_location.php" method="post" style="display: none;">
                <input type="hidden" name="delete_location" value="<?php echo $locationHandler->getLocationIdByName($location); ?>">
            </form>
        </div>
    <?php endforeach; ?>
</div>
</form>
</div>   
<a href="location.php" class="go-back-button" type="button">Go Back</a>

</body>
</html>

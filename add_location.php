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
}

// Delete location
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_location'])) {
    $locationId = $_POST['delete_location'];
    
    // Database connection
    $locationHandler = new Location();
    
    // Delete location
    $locationHandler->deleteLocation($locationId);
}

// Get existing locations
$locationHandler = new Location();
$existingLocations = $locationHandler->getExistingLocations();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add location</title>
    <link rel="stylesheet" href="styles/location.css">

    <script>
         function confirmDelete(event, locationId) {
        event.stopPropagation();
        var form = document.getElementById('delete_location_' + locationId);
        if (form) {
            if (confirm("Are you sure you want to delete this location?")) {
                form.submit();
            }
        } else {
            console.error("Form with ID delete_location_" + locationId + " not found.");
        }
    }
    </script>
</head>
<body>

<h1>Add location</h1>
<div class="form-container">
<form action="add_location.php" method="post" enctype="multipart/form-data">
    <div class="profile-picture" title="Profile Picture"></div>
    
    <div class="form-group-add">
        <label for="location">Location</label>
        <input type="text" id="new_location" name="new_location" autocomplete="off">
        <button type="submit" class="add-button">Add location</button>
    </div>
    </form>

    <div class="form-group">
    <label for="location">Existing location</label>
    <?php foreach ($existingLocations as $location): ?>
        <div class="form-group-content">
            <input type="text" name="existing_location[]" value="<?php echo $location; ?>" readonly>
            <button type="button" class="delete-button" onclick="confirmDelete(event, <?php echo $locationHandler->getLocationIdByName($location); ?>)">Delete location</button>
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

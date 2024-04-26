<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

function getExistingLocations() {
    // Database connection
    require_once __DIR__ . "/classes/Db.php";
    $db = new Db();
    $conn = $db->getConnection();

    // Prepare and execute SQL query to select cities from the locations table
    $stmt = $conn->prepare("SELECT city FROM locations");
    $stmt->execute();

    // Fetch all rows as associative array
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Extract city names from the associative array
    $cityNames = array_column($locations, 'city');

    return $cityNames;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['new_location']) && !empty($_POST['new_location'])) {
        $locationName = $_POST['new_location'];
        
          // Database connection
          require_once __DIR__ . "/classes/Db.php";
          require_once __DIR__ . "/classes/Location.php";
          
          // Instantiate Location class
          $locationHandler = new Location();
          
          // Add new location and get its ID
          $locationId = $locationHandler->addLocation($locationName);
  
          // Update user's location
          $locationHandler->updateLocation($_SESSION['user']['location_id'], $locationId);
          header("Location: " . $_SERVER['REQUEST_URI']);

          echo "Location added successfully.";
      } else {
          echo "Location name is required.";
      }
  }
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add location</title>
    <link rel="stylesheet" href="styles/location.css">
    <script src="script/location.js"></script>
</head>
<body>

<h1>Add location</h1>

<form action="add_location.php" method="post" enctype="multipart/form-data">
    <div class="profile-picture" title="Profile Picture"></div>
    
     <div class="form-group-add">
         <label for="location">Location</label>
         <input type="text" id="new_location" name="new_location" autocomplete="off">
         <button type="submit" onclick="validateAndSubmit(event)" class="add-button">Add location</button>
    </div>

    
    <div class="form-group">
        <label for="location">Existing location</label>
        <?php
        // Display existing locations
        $existingLocations = getExistingLocations();
        foreach ($existingLocations as $index => $location) {
            ?>
     <div class="form-group-content" id="location<?php echo $locationId; ?>">
    <input type="text" name="existing_location<?php echo $index + 1; ?>" value="<?php echo $location; ?>" readonly>
    <button type="button" class="edit-button">Edit location</button>
    <button type="button" class="delete-button" data-location-id="<?php echo $locationId; ?>">Delete location</button>
</div>
            <?php
        }
        ?>
        <div class="form-group-content" id="newLocationContainer"></div> <!-- Container for new location -->
    </div>

</form>
    
    <a href="dashboard.php" class="go-back-button" type="button">Go Back</a>


</body>
</html>

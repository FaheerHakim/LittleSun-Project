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
    <style>
     body {
    font-family: Arial, sans-serif;
    padding: 20px;
    margin: 0;
    /* Adjusted gradient for a longer transition to white */
    background: linear-gradient(to bottom, #3498db 10%, white 90%);
    color: white;
}

h1 {
    text-align: center;
}

form {
    background-color: white;
    color: black;
    padding: 20px;
    margin-top: 20px;
    border-radius: 10px;
    min-height: 85vh; 
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
}

.form-group input,
.form-group select {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    color: black;
    margin: 4px;
}



.go-back-button {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 10px;
    background-color: #3498db; /* Blue button for 'Go Back' */
    border: none;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.go-back-button:hover {
    background-color: #2980b9;
}

.add-button {
    padding: 10px;
    margin: 10px;
    background-color: #3498db;/* Red button */
    border: none;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.add-button:hover {
    background-color: blue;
}


.delete-button {
    padding: 10px;
    margin: 10px;
    background-color: #e74c3c; /* Red button */
    border: none;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.delete-button:hover {
    background-color: #c0392b;
}

    </style>


</head>
<body>

<h1>Add location</h1>

<form action="add_location.php" method="post" enctype="multipart/form-data">
    <div class="profile-picture" title="Profile Picture"></div>
    
    <div class="form-group">
        <label for="location">Location</label>
        <input type="location" id="location" name="location" >
        <button type="submit" onclick="addLocation(this)" class="add-button">Add location</button>

    </div>
  

    
    <div class="form-group">
        <label for="location">Existing location</label>
        <div class="form-group-content">
            <input type="text" name="location" id="location" value="Mechelen">
            <button type="submit" class="add-button">Edit location</button>
            <button onclick="deleteLocation(this)" class="delete-button">Delete location</button>
        </div>
        <div class="form-group-content">
            <input type="text" name="location" id="location" value="Antwerpen">
            <button type="submit" class="add-button">Edit location</button>
            <button onclick="deleteLocation(this)" class="delete-button">Delete location</button>
        </div>
    </div>
</form>

<a href="dashboard.php" class="go-back-button" type="button">Go Back</a>


</body>
<script>

function addLocation(button) {
    // Get the parent div of the button
    var parentDiv = button.parentNode;

    // Remove the parent div
    parentDiv.parentNode.appendChild(parentDiv);
}
    
function deleteLocation(button) {
    // Get the parent div of the button
    var parentDiv = button.parentNode;

    // Remove the parent div
    parentDiv.parentNode.removeChild(parentDiv);
}
</script>
</html>
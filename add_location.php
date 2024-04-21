<?php
session_start();

// Initialize locations array if not set
if (!isset($_SESSION['locations'])) {
    $_SESSION['locations'] = [];
}

// Check if deleting a location
if (isset($_POST['delete_location'])) {
    $location_to_delete = $_POST['delete_location'];
    // Remove the location from the array
    $_SESSION['locations'] = array_filter(
        $_SESSION['locations'],
        function($location) use ($location_to_delete) {
            return $location !== $location_to_delete; // Keep only locations that don't match the one to delete
        }
    );
}

// Check if adding a new location
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["location"])) {
    $new_location = htmlspecialchars($_POST["location"]);
    if ($new_location) {
        $_SESSION['locations'][] = $new_location;
    }
}

// Get the current locations
$existing_locations = $_SESSION['locations'];
?>


<!DOCTYPE html>
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

<form action="" method="post">
    <div class="form-group">
        <label for="location">New Location</label>
        <input type="text" id="location" name="location">
        <button type="submit" class="add-button">Add location</button>
    </div>

    <div>
        <h2>Existing Locations</h2>
        <?php
        // Display existing locations in the list
        foreach ($existing_locations as $loc) {
            echo "<div class='form-group-content'>
                    <input type='text' name='existing_location' value='$loc'>
                    <button type='submit' class='add-button'>Edit location</button>
                    <button type='button' class='delete-button' onclick='deleteLocation(this)'>Delete location</button>
                </div>";
        }
        ?>
    </div>
</form>

<a href="dashboard.php" class="go-back-button" type="button">Go Back</a>


<script>
function deleteLocation(button) {
    var parentDiv = button.parentNode;
    parentDiv.parentNode.removeChild(parentDiv);
}
</script>
</body>
</html>
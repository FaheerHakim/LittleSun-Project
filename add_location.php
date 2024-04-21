<?php
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data

    $location = htmlspecialchars($_POST["location"]);

 
}

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

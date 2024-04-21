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
    <title>Add Manager</title>
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
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    color: black;
    width: 98%;
}

.profile-picture {
    display: block;
    width: 100px;
    height: 100px;
    border-radius: 50%; /* Circular shape */
    border: 2px solid #3498db;
    background-color: white;
    background-size: cover; /* Cover to maintain aspect ratio */
    background-position: center;
    margin: 0 auto; /* Center it horizontally */
}

.add-button {
    padding: 10px;
    background-color: #e74c3c; /* Red button */
    border: none;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.add-button:hover {
    background-color: #c0392b;
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

    </style>


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
            <option value="Duitsland">Duitsland</option>
            <option value="Zambia">Zambia</option>
            <option value="Kinshasa">Kinshasa</option>
            <option value="België">België</option>
            <option value="Portugal">Portugal</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="profile-picture">Upload Profile Picture</label>
        <input type="file" id="profile-picture" name="profile_picture" accept="image/*">
    </div>

    <button type="submit" class="add-button">Add Manager</button>

    <<a href="dashboard.php">Edit manager profile button type="button" class="go-back-button" onclick=location: dashboard.php>Go Back</a>
</form>

</body>
</html>

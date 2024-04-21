<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verzamelen van formuliergegevens
    $first_name = htmlspecialchars($_POST["first_name"]);
    $last_name = htmlspecialchars($_POST["last_name"]);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = !empty($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : null; // alleen als wachtwoord wordt gewijzigd
    $location = htmlspecialchars($_POST["location"]);

    // Profielafbeelding uploaden
    $profile_picture = $_FILES["profile_picture"];
    $upload_dir = "uploads/";
    $upload_file = $upload_dir . basename($profile_picture["name"]);

    if ($profile_picture["tmp_name"]) {
        move_uploaded_file($profile_picture["tmp_name"], $upload_file);
    }

    // Database-update-logica hier, gebruik bijv. een unieke ID om de gebruiker te identificeren
    // Bijvoorbeeld: UPDATE users SET ... WHERE user_id = ?

    echo "Profiel bijgewerkt.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit profile</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
        margin: 0;
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
        border-radius: 50%;
        border: 2px solid #3498db;
        background-color: white;
        background-size: cover;
        background-position: center;
        margin: 0 auto;
    }

    .update-button {
        padding: 10px;
        background-color: #3498db; 
        border: none;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .update-button:hover {
        background-color: #2980b9;
    }

    .go-back-button {
        padding: 10px;
        background-color: #e74c3c; 
        border: none;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .go-back-button:hover {
        background-color: #c0392b;
    }
    </style>
</head>
<body>
    <h1>Edit manager profile</h1>
    <form action="edit_profile.php" method="post" enctype="multipart/form-data">
        <div class="profile-picture" title="Profielafbeelding"></div>
        
        <input type="hidden" name="user_id" value="<!-- plaats hier de user ID -->">
        
        <div class="form-group">
            <label voor="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label voor="password">Password</label>
            <input type="password" id="password" name="password">
        </div>

        <div class ="form-group">
            <label voor="first-name">First name</label>
            <input type="text" id="first-name" name="first_name" required>
        </div>

        <div class="form-group">
            <label voor="last-name">Last name</label>
            <input type="text" id="last-name" name="last_name" required>
        </div>

        <div class="form-group">
            <label voor="location">Locatie</label>
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
            <label voor="profile-picture">Upload profile picture</label>
            <input type="file" id="profile-picture" name="profile_picture" accept="image/*">
        </div>

        <button type="submit" class="update-button">Save</button>
        
        <a href="dashboard.php" class="go-back-button" type="button">Go Back</a>
    </form>
</body>
</html>


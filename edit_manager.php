<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verzamelen van formuliergegevens
  
    $password = !empty($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : null; // alleen als wachtwoord wordt gewijzigd
    $Selectmanager = htmlspecialchars($_POST["Selectmanager"]);


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
    <h1>Reset manager password</h1>
    <form action="edit_profile.php" method="post" enctype="multipart/form-data">
      
        
    

        <div class="form-group">
            <label voor="location">Select manager</label>
            <select id="location" name="location" required>
                <option value="">Select manager</option>
                <option value="Charlotte">Charlotte</option>
                <option value="Milana">Milana</option>
                <option value="Dante">Dante</option>
                <option value="Jonas">Jonas</option>
               
            </select>
        </div>

        <div class="form-group">
            <label voor="password">Reset Password</label>
            <input type="password" id="password" name="password">
        </div>
        
    

        <button type="submit" class="update-button">Reset password</button>
        
        <a href="dashboard.php" class="go-back-button" type="button">Go Back</a>
    </form>
</body>
</html>


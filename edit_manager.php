<?php
include 'logged_in.php';

include 'permission_admin.php';

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
    <link rel="stylesheet" href="styles/manager.css">
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
        
    
        <a href="manager.php" class="update-button">Reset password</a>
        
        <a href="manager.php" class="go-back-button" type="button">Go Back</a>
    </form>
</body>
</html>


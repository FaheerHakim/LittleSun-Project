<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'logged_in.php';

include 'permission_admin.php';


?><!DOCTYPE html>
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
        
    
        <a href="user.php" class="update-button">Reset password</a>
        
        <a href="user.php" class="go-back-button" type="button">Go Back</a>
    </form>
</body>
</html>


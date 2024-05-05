<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once 'classes/User.php';
include 'logged_in.php'; // Check if user is logged in
include 'permission_admin.php'; // Check if user has admin permissions

$user = new User();

// Get user_id from query parameters
$userId = $_GET['user_id'] ?? null; // Use null coalescing operator

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'];

    if (!empty($userId) && !empty($newPassword)) {
        if ($user->updateUserPassword($userId, $newPassword)) {
            $successMessage = "Password updated successfully.";
        } else {
            $errorMessage = "Failed to update password.";
        }
    } else {
        $errorMessage = "User ID and password cannot be empty.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Password</title>
    <!-- Include CSS and JS as needed -->
</head>
<body>
    <!-- Display success or error messages if set -->
    <?php if (isset($errorMessage)): ?>
        <p style="color: red;"><?= htmlspecialchars($errorMessage) ?></p>
    <?php elseif (isset($successMessage)): ?>
        <p style="color: green;"><?= htmlspecialchars($successMessage) ?></p>
    <?php endif; ?>

    <!-- Form for editing the password -->
    <form method="post">
        <input type="hidden" name="user_id" value="<?= isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_id']) : '' ?>">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required>
        <button type="submit">Update Password</button>
    </form>

</body>
<style>
    /* styles.css */

/* Base styles for the form */
form {
    max-width: 400px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
}

/* Styling the label and input elements */
label {
    display: block;
    font-size: 16px;
    margin-bottom: 10px;
    color: #333;
}

input {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 20px;
}

/* Styling the submit button */
button {
    padding: 10px 20px;
    font-size: 16px;
    color: white;
    background-color: #007BFF;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

/* Styles for success and error messages */
.success-message {
    color: green;
    font-size: 16px;
    text-align: center;
}

.error-message {
    color: red;
    font-size: 16px;
    text-align: center;
}

</style>
</html>

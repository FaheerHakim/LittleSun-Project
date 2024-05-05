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
    <link rel="stylesheet" href="styles/edit_password.css">
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

</html>

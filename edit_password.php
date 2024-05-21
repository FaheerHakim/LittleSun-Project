<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once 'classes/User.php';
include 'logged_in.php'; 
include 'permission_admin.php'; 

$user = new User();

$userId = $_GET['user_id'] ?? null; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'];

    if (!empty($userId) && !empty($newPassword)) {
        if ($user->updateUserPassword($userId, $newPassword)) {
            $_SESSION['message'] = "Location added successfully.";
            $_SESSION['message_type'] = "success";
            header("Location: message_edit_password.php");        
        } else {
            $errorMessage = "Failed to update password.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Password</title>
    <link rel="stylesheet" href="styles/edit_passwords.css">
    <script src="script/edit_password.js" defer></script>
</head>
<body>
<h1>Edit password</h1>

    <?php if (isset($errorMessage)): ?>
        <p style="color: red;"><?= htmlspecialchars($errorMessage) ?></p>
    <?php elseif (isset($successMessage)): ?>
        <p style="color: green;"><?= htmlspecialchars($successMessage) ?></p>
    <?php endif; ?>
    <div class="form">
    <div class="form-container">
        <form id="edit-password-form" method="post">
            <input type="hidden" name="user_id" value="<?= isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_id']) : '' ?>">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required>
            <button type="button" class="edit-button" onclick="confirmEdit()">Change Password</button>
        </form>
    </div>
    <a href="edit_manager.php" class="go-back-button" type="button">Go Back</a>
</div>

<div id="confirm-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p>Are you sure you want to edit the password?</p>
        <div class="modal-buttons">
            <button class="button no" onclick="closeModal()">Cancel</button>
            <button class="button yes" onclick="submitForm()">Yes, Change</button>
        </div>
    </div>
</div>
</body>

</html>

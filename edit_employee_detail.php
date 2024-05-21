<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/Location.php";

include 'logged_in.php';
include 'permission_manager.php';

if (!isset($_GET['user_id'])) {
    header("Location: all_employees.php");
    exit();
}

$user_id = $_GET['user_id'];
$user = new User();
$userInfo = $user->getUserById($user_id);

$locationName = "Location does not exist";
$locationHandler = new Location();
$locationInfo = $locationHandler->getLocationById($userInfo['location_id']);

if ($locationInfo) {
    $locationName = $locationInfo['city'];
}

$profilePicture = "../LittleSun-Project/images/profile.jpg";
if (!empty($userInfo['profile_picture'])) {
    $profilePicture = $userInfo['profile_picture'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user->updateUser($user_id, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['location']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $user->deleteUser($user_id);
    header("Location: all_employees.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/edit_manager_detail.css">
    <script src="script/edit_manager.js" defer></script>
    <title>View profile</title>
</head>
<body>

<h1>View employee profile</h1>

<div class="form">
    <form action="" method="post">
        <div class="form-container">
            <div class="form-group">
                <img src="<?= htmlspecialchars($profilePicture) ?>" alt="Profile Picture" readonly>
            </div>
            <div class="form-group">
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="first_name" value="<?= htmlspecialchars($userInfo['first_name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="last_name" value="<?= htmlspecialchars($userInfo['last_name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($userInfo['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" value="<?= htmlspecialchars($locationName) ?>" required>
            </div>
            <div class="form-group">
                <button type="button" class="delete-button" onclick="confirmDelete(<?php echo $user_id; ?>)">Delete employee</button>
            </div>
        </div>
    </form>
    <form id="delete_location_<?php echo $user_id; ?>" action="confirm_delete_manager.php" method="post" style="display: none;">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
    </form>

    <a href="edit_manager.php" class="go-back-button" type="button">Go Back</a>
</div>

</body>
</html>

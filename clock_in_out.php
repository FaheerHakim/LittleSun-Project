<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'logged_in.php';
include 'permission_employee.php';

require_once __DIR__ . "/classes/WorkHours.php"; 


$workHoursHandler = new WorkHours();


$user = $_SESSION['user'];
$userId = $user['user_id'];

date_default_timezone_set('Europe/Brussels');
if (isset($_POST['clock_in'])) {
  
    $currentTime = date("Y-m-d H:i:s");
    $workHoursHandler->clockIn($userId, $currentTime);
    $_SESSION['message'] = "You have successfully clocked in for today.";
    header("Location: clock_in_out.php");
exit();
}


if (isset($_POST['clock_out'])) {
 
    $currentTime = date("Y-m-d H:i:s");
    $workHoursHandler->clockOut($userId, $currentTime);
    $_SESSION['message'] = "You have successfully clocked out for today.";
    header("Location: clock_in_out.php");
    exit();
}

$isClockedIn = $workHoursHandler->isClockedIn($userId);

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';


unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Clock In/Out</title>
    <link rel="stylesheet" href="styles/clock_in_out.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script/clock_in_out.js"></script>
</head>
<body>
<a href="dashboard.php" class="go-back-button" type="button">Go Back</a>
<h1>Clock In/Out</h1>
<div class="container">
    <div class="form-container">
        <form action="clock_in_out.php" method="post">
            <p id="currentTime"></p>
            <?php if ($isClockedIn): ?>
                <button type="submit" name="clock_out">Clock Out</button>
            <?php else: ?>
                <button type="submit" name="clock_in">Clock In</button>
            <?php endif; ?>
        </form>
        <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    </div>
</div>
</body>
</html>

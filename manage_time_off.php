<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/TimeOff.php";

include 'logged_in.php';

include 'permission_manager.php';

// Instantiate TimeOff class
$timeOffHandler = new TimeOff();

// Approve or decline time off request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['request_id'])) {
    $requestId = $_POST['request_id'];
    $action = $_POST['action'];
    
    // Perform action based on user's choice
    if ($action === 'approve') {
        $timeOffHandler->approveTimeOffRequest($requestId);
    } elseif ($action === 'decline') {
        $timeOffHandler->declineTimeOffRequest($requestId);
    }
}

// Get time off requests
$timeOffRequests = $timeOffHandler->getTimeOffRequests();

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Time Off Requests</title>
</head>
<body>
<h1>Manage Time Off Requests</h1>

<table>
    <tr>
        <th>User</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php foreach ($timeOffRequests as $request): ?>
        <tr>
            <td><?php echo $request['user_id']; ?></td>
            <td><?php echo $request['start_date']; ?></td>
            <td><?php echo $request['end_date']; ?></td>
            <td><?php echo $request['status']; ?></td>
            <td>
                <form action="manage_time_off.php" method="post">
                    <input type="hidden" name="request_id" value="<?php echo $request['time_off_request_id']; ?>">
                    <select name="action">
                        <option value="approve">Approve</option>
                        <option value="decline">Decline</option>
                    </select>
                    <button type="submit">Submit</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Add additional HTML or JavaScript as needed -->
</body>
</html>
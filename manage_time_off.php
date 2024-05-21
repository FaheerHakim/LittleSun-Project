<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/TimeOff.php";

include 'logged_in.php';

include 'permission_manager.php';

$user = new User(); 


$timeOffHandler = new TimeOff();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['request_id'])) {
    $requestId = $_POST['request_id'];
    $action = $_POST['action'];
    

    if ($action === 'approve') {
        $timeOffHandler->approveTimeOffRequest($requestId);
    } elseif ($action === 'decline') {
        $timeOffHandler->declineTimeOffRequest($requestId);
    }
}


$timeOffRequests = $timeOffHandler->getTimeOffRequests();

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Time Off Requests</title>
    <link rel="stylesheet" href="styles/manage_time.css">
</head>
<body>
<h1>Manage Time Off Requests</h1>


<div class="container">


<table>
    <tr>
        <th>User</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Reason</th>
        <th>Additional Notes</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php foreach ($timeOffRequests as $request): ?>
        <tr>
            <td>
                <?php
                $userDetails = $user->getUserById($request['user_id']);
                echo htmlspecialchars($userDetails['first_name'] . ' ' . $userDetails['last_name']);
                ?>            
            </td>
            <td><?php echo $request['start_date']; ?></td>
            <td><?php echo $request['end_date']; ?></td>
            <td><?php echo $request['reason']; ?></td>
            <td><?php echo $request['additional_notes']; ?></td>
            <td><?php echo $request['status']; ?></td>
            <td>
                <form action="manage_time_off.php" method="post">
                    <input type="hidden" name="request_id" value="<?php echo $request['time_off_request_id']; ?>">
                    <select name="action">
                        <option value="" disabled selected>Select an action</option>
                        <option value="approve">Approve</option>
                        <option value="decline">Decline</option>
                    </select>
                    <button type="submit">Submit</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</div>
<a href="dashboard.php" class="go-back" type="button">Go Back</a>

</body>

</html>

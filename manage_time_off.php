<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/TimeOff.php";

include 'logged_in.php';

include 'permission_manager.php';

$user = new User(); // Instantiate the User class

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
<div class="container">

<a href="time_off_request_manager.php" class="go-back" type="button">Go Back</a>
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
            <td>
                <?php
                $userDetails = $user->getUserById($request['user_id']);
                echo htmlspecialchars($userDetails['first_name'] . ' ' . $userDetails['last_name']);
                ?>            
            </td>
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
</div>
<!-- Add additional HTML or JavaScript as needed -->
</body>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            font-size: 24px;
            padding: 20px;
            background-color: #34495e;
            color: white;
            margin: 0;
        }
        .container {
            padding: 20px;
            margin: auto;
            width: 80%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            text-align: left;
            padding: 12px;
        }
        th {
            background-color: #34495e;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e1e1e1;
        }
        select, button {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            background-color: #ffffff;
        }
        button {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        
        .go-back {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .go-back:hover {
            background-color: #2980b9;
        }
    </style>
</html>

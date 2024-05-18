<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/WorkHours.php";

$userHandler = new User();
$workHoursHandler = new WorkHours();

include_once 'logged_in.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employeeId = $_POST['user_id'];
    $selectedMonth = $_POST['selected_month'];

    // Fetch work hours data for the selected employee and month
    $workHoursData = [];
    $userData = $userHandler->getUserById($employeeId);
    $userWorkHours = $workHoursHandler->getWorkHoursForMonth($employeeId, $selectedMonth);
    $workHoursData[] = [
        'first_name' => $userData['first_name'],
        'last_name' => $userData['last_name'],
        'work_hours' => $userWorkHours
    ];
}

// Fetch all employees for the dropdown
$employees = $userHandler->getEmployeeUsers();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Work Hours Report</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Employee Work Hours Report</h2>
    <form method="post">
        <label for="user_id">Select Employee:</label>
        <select name="user_id" id="user_id" required>
            <option value="">Select Employee</option>
            <?php foreach ($employees as $employee): ?>
                <option value="<?php echo $employee['user_id']; ?>"><?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?></option>
            <?php endforeach; ?>
        </select>
        <label for="selected_month">Select Month:</label>
        <input type="month" id="selected_month" name="selected_month" required>
        <button type="submit">Generate Report</button>
    </form>

    <?php if (isset($workHoursData) && !empty($workHoursData)): ?>
    <?php foreach ($workHoursData as $employeeData): ?>
        <?php 
        $employeeName = $employeeData['first_name'] . ' ' . $employeeData['last_name'];
        ?>
        <h3>Work Hours Report for <?php echo $employeeName; ?> in <?php echo date('F Y', strtotime($selectedMonth)); ?></h3>
        <table>
            <tr>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Total work hours per shift</th>
            </tr>

            <?php 
           $totalHours = 0; 
           foreach ($employeeData['work_hours'] as $workHour): 

            $totalHours += $workHour['total_hours']; 
               $totalHoursInt = floor($workHour['total_hours']); 
               $totalMinutes = round(($workHour['total_hours'] - $totalHoursInt) * 60); 
               $totalTime = sprintf("%02d:%02d", $totalHoursInt, $totalMinutes); 
       ?>
               <tr>
                   <td><?php echo date('Y-m-d', strtotime($workHour['start_time'])); ?></td>
                   <td><?php echo date('H:i', strtotime($workHour['start_time'])); ?></td>
                   <td><?php echo date('H:i', strtotime($workHour['end_time'])); ?></td>
                   <td><?php echo $totalTime; ?></td> 
               </tr>
           <?php endforeach; ?>
       
           <tr>
               <td colspan="3"><strong>Total work hours this month:</strong></td>
               <?php 
                   $totalHoursInt = floor($totalHours); 
                   $totalMinutes = round(($totalHours - $totalHoursInt) * 60); 
       
                   $totalTime = sprintf("%02d:%02d", $totalHoursInt, $totalMinutes); 
               ?>
               <td><?php echo $totalTime; ?></td> 
           </tr>
       </table>
       <?php endforeach; ?>
<?php endif; ?>

</body>
</html>

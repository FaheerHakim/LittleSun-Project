<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/manager.css">
    <title>Message</title>
    <style>
        body{
            background-color: #f2F2F2;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .message-container {
            background-color: white;
            padding: 20px;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            font-family: Arial, sans-serif;
            text-align: center;
            min-width: 400px;
            min-height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 6px;
        }
        .message p{
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .message.success {
            color: #155724;
        }
        .message.error {
            color: #721c24;
        }

        .button{
            padding: 10px 20px;
            text-decoration: none;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 16px;
        }
        .button.continue{
            background-color: #65b665;
        }
        .button.continue:hover{
            background-color: #7bc77b;
        }
        .button.again{
            background-color: #e74c3c;
        }
        .button.again:hover{
            background-color: #c0392b;
        }
        
    </style>
</head>
<body>

<div class="message-container">
<?php
if (isset($_SESSION['message'])) {
    $message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';
    echo '<p class="message ' . $message_type . '">' . $_SESSION['message'] . '</p>';
    unset($_SESSION['message']); 
    unset($_SESSION['message_type']); 
}
?>

<div class="button-container">
    <?php if ($message_type == 'success'): ?>
        <a href="dashboard.php" class="button continue">Continue</a>
    <?php else: ?>
        <a href="add_manager.php" class="button again">Try Again</a>
    <?php endif; ?>
</div>
</div>
</body>
</html>

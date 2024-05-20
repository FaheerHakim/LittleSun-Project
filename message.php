<?php
session_start();
if (isset($_SESSION['message'])) {
    $message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';
}
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
            gap: 24px;
        }
        p.message{
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
            margin-top: 24px;
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
        .image-container{
            margin-bottom: 12px;
        }
        
    </style>
</head>
<body>

<div class="message-container">
    <div class="image-container">
        <?php if ($message_type == 'success'): ?>
            <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="48.5" fill="#13C39C" stroke="#25FFAE" stroke-width="3"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M75.1743 34.1417L46.514 69.977L24 51.2131L28.2479 46.1156L45.5582 60.5386L69.9971 30L75.1743 34.1417V34.1417Z" fill="white"/>
            </svg>
        <?php else: ?>
            <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="48.5" fill="#FB4B4B" stroke="#F6BDBD" stroke-width="3"/>
            <path d="M67 67L33 33M67 33L33 67" stroke="white" stroke-width="5" stroke-linecap="round"/>
            </svg>
        <?php endif; ?>
    </div>
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

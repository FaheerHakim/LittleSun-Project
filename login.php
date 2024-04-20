<?php
session_start();
require_once __DIR__ . "/classes/User.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User();
    $loggedInUser = $user->login($email, $password);

    if ($loggedInUser) {
        $_SESSION['user'] = $loggedInUser;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Little sun login</title>
    <link rel="stylesheet" href="styles/normalize.css">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-screen">
        <div class="login-left">
            <img class="login-screen-image" src="images/startpagina.png" alt="login">
        </div>
        <div class="login-right">
            <div class="login-form">
                <h1>Login to your account</h1>
                <form action="login.php" method="post">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Email" required>
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Login</button>
                </form>
        </div>
    </div>
</body>
</html>

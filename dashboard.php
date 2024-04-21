<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// Display different content based on user type
function displayDashboard($user) {
    switch ($user['typeUser']) {
        case 'admin':
            echo "Welcome Admin! You have access to admin functionalities.";
            break;
        case 'manager':
            echo "Welcome Manager! You have access to manager functionalities.";
            break;
        case 'employee':
            echo "Welcome Employee! You have access to employee functionalities.";
            break;
        default:
            echo "Unknown user type.";
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Little Sun | Home</title>
    
    <link rel="stylesheet" href="styles/home.css">
</head>

<body>
    <!--  Navigatie  -->
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                        </span>
                        <span class="title">Little Sun</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title"> Dashboard <?php echo $user['typeUser'] ;?></span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Managers</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Employees</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="chatbubble-outline"></ion-icon>
                        </span>
                        <span class="title">Messages</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="lock-closed-outline"></ion-icon>
                        </span>
                        <span class="title">Password</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <a href="logout.php">Logout</a>
                    </a>
                </li>
            </ul>
        </div>

        <!--  Main  -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <div class="search">
                    <label>
                        <input type="text" placeholder="Search here">
                        <ion-icon name="search-outline"></ion-icon>
                    </label>
                </div>

                <div class="user">
                    <img src="images/profile.jpg" alt="">
                </div>
            </div>

            <!--  Cards  -->
            <div class="cardBox">
                <div class="card">
                    <div>
                        <div class="numbers">0000</div>
                        <div class="cardName">Total Managers</div>
                    </div>

                    <div class="iconBx">
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers">1111</div>
                        <div class="cardName">Total Employees</div>
                    </div>

                    <div class="iconBx">
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers">2222</div>
                        <div class="cardName">Sales</div>
                    </div>

                    <div class="iconBx">
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers">3333</div>
                        <div class="cardName">Earning</div>
                    </div>

                    <div class="iconBx">
                    </div>
                </div>
            </div>
            <?php displayDashboard($user); ?>

            <!--  Locations  -->
            <div class="details">
                <div class="recentLocations">
                    <div class="cardHeader">
                        <h2>Recent Locations</h2>
                        <a href="#" class="btn">Add Location</a>
                    </div>

                </div>

                <!--  New Managers  -->
                <div class="recentManagers">
                    <div class="cardHeader">
                        <h2>Recent Managers</h2>
                    </div>

                    <table>
                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/profile.jpg" alt=""></div>
                            </td>
                            <td>
                                <h4>Sarah <br> <span>Italy</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/profile.jpg" alt=""></div>
                            </td>
                            <td>
                                <h4>Lara <br> <span>The Netherlands</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/profile.jpg" alt=""></div>
                            </td>
                            <td>
                                <h4>Faheer <br> <span>Syria</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/profile.jpg" alt=""></div>
                            </td>
                            <td>
                                <h4>Charlotte <br> <span>Belgium</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/profile.jpg" alt=""></div>
                            </td>
                            <td>
                                <h4>Milana <br> <span>Belgium</span></h4>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

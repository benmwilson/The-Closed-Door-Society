<?php

session_start();

// This page can only be viewed if no user is logged in

if (isset($_SESSION['userid'])) {
    $userID = $_SESSION['userid'];
    header("Location: profile.php?id=$userID");
    die();
}

include_once 'database.php';

?>

<!DOCTYPE html>
<html lang=en>

<head>
    <title>TCDS</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

    <div class="header">
        <div class="left-header">
            <h1>The Closed Door Society</h1>
            <nav>
                <a class="header-nav" href="main.php">Home</a>
            </nav>
        </div>
        <?php include 'userinfo.php'; ?>
    </div>

    <div class="main">

        <div class="sidebar">
            <h2>Behind The Closed Doors</h2>
            <p>Always watching</p>
            <img src="img/logo.jpg" alt="" width="250" height="250">
        </div>

        <div class="content">

            <h2>Sign Up To The Closed Door Society</h2>
            <form action="newuser.php" method="POST" enctype="multipart/form-data">
                <table>
                    <tr>
                        <h4>
                            <td><label for="username">Username:</label></td>
                            <td><input type="text" name="username" id="username" maxlength="64" required></td>
                        </h4>
                    </tr>
                    <tr>
                        <h4>
                            <td><label for="email">Email:</label></td>
                            <td><input type="email" name="email" id="email" required></td>
                        </h4>
                    </tr>
                    <tr>
                        <h4>
                            <td><label for="password">Password:</label></td>
                            <td><input type="password" name="password" id="password" required></td>
                        </h4>
                    </tr>
                    <tr>
                        <h4>
                            <td><label for="userimg">User Image:</label></td>
                            <td><input type="file" id="userimg" name="userimg" accept="image/png"></td>
                        </h4>
                    <tr>
                        <h4>
                            <td><input type="submit" value="Submit"></td>
                        </h4>
                    </tr>
                </table>
            </form>

        </div>

    </div>

    <div class="footer">
        <h3>&copy; The Closed Door Society</h3>
    </div>

</body>

</html>
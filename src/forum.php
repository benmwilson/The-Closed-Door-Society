<?php
session_start();
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

            <!-- List forums/threads here, use function from database.php -->

            <p> forums/threads go here </p>

        </div>

    </div>

    <div class="footer">
        <h3>&copy; The Closed Door Society</h3>
    </div>

</body>

</html>
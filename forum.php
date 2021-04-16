<?php
session_start();
include_once 'database.php';
include_once 'render.php';
?>

<!DOCTYPE html>
<html lang=en>

<head>
    <title>TCDS</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
</head>

<body>

    <?php displayHeader(); ?>

    <div class="main">


        <div class="content">

            <!-- List forums/threads here, use function from database.php -->
            <?php displayThreadTitle($_GET['id']) ?>
            <?php listThreads($_GET['id']) ?>

        </div>

        <div class="sidebar">
            <?php displayNews(); ?>
            <br>
            <?php displayRecentComments(); ?>
            <br>
            <?php displayHotComments(); ?>
        </div>
    </div>

    <div class="footer">
        <h3>&copy; The Closed Door Society</h3>
    </div>

</body>

</html>
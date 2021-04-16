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

        

        <div class="content">

            <!-- List comments here, use function from database.php -->
            <?php displayThreadTitle($_GET['id']) ?>
            <?php listComments($_GET['id']) ?>
        
        </div>

        <div class="sidebar">
			<?php displayNews(1); ?>
		</div>

    </div>

    <div class="footer">
        <h3>&copy; The Closed Door Society</h3>
    </div>

</body>

</html>
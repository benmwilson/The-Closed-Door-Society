<?php

session_start();

// This page can only be viewed if no user is logged in

// if (isset($_SESSION['userid'])) {
//     $userID = $_SESSION['userid'];
//     header("Location: profile.php?id=$userID");
//     die();
// }

include_once 'database.php';
include_once 'render.php';

?>

<!DOCTYPE html>
<html lang=en>

<head>
    <title>TCDS</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">
        if (typeof jQuery == 'undefined') {
            document.write(unescape("%3Cscript src='/js/jquery-3.6.0.min.js' type='text/javascript'%3E%3C/script%3E"));
        }
    </script>
    <script src="./js/validation.js"></script>
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

            <h2>Add News</h2>
            <form id="news-form" action="newnews.php" method="POST" enctype="multipart/form-data">
                <table>
                    <tr>
                        <h4>
                            <td><label for="title">News Title:</label></td>
                            <td><input type="text" name="title" id="title" maxlength="20"></td>
                        </h4>
                    </tr>
                    <tr>
                        <h4>
                            <td><label for="content">News Content:</label></td>
                            <td><textarea name="content" rows="10" cols="30" type="text" name="content" id="content" maxlength="150"></textarea></td>
                        </h4>
                    </tr>
                    <tr>
                        <h4>
                            <td><label for="newsimg">News Image:</label></td>
                            <td><input type="file" id="newsimg" name="newsimg" accept="image/png"></td>
                        </h4>
                    <tr>
                        <h4>
                            <td><input type="submit" value="Submit"></td>
                        </h4>
                    </tr>
                </table>
            </form>

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
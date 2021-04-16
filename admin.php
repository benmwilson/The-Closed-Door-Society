<?php
session_start();
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

	<?php displayHeader(); ?>

	<div class="main">


		<div class="content">

            <?php
                if(isAdmin()){
                    echo "<h2>CDS Admin Panel</h2>";
                    echo "<br>";
                    // display the news form
                    echo "<h2>Add News</h2>";
                    echo "<form id='news-form' action='newnews.php' method='POST' enctype='multipart/form-data'>";
                    echo "<table>";
                    echo "<tr>";
                    echo "<h4>";
                    echo "<td><label for='title'>News Title:</label></td>";
                    echo "<td><input type='text' name='title' id='title' maxlength='20'></td>";
                    echo "</h4>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<h4>";
                    echo "<td><label for='content'>News Content:</label></td>";
                    echo "<td><textarea name='content' rows='10' cols='30' type='text' name='content' id='content' maxlength='150'></textarea></td>";
                    echo "</h4>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<h4>";
                    echo "<td><label for='newsimg'>News Image:</label></td>";
                    echo "<td><input type='file' id='newsimg' name='newsimg' accept='image/png'></td>";
                    echo "</h4>";
                    echo "<tr>";
                    echo "<h4>";
                    echo "<td><input type='submit' value='Submit'></td>";
                    echo "</h4>";
                    echo "</tr>";
                    echo "</table>";
                    echo "</form>";
                    echo "<br>";
                    // display the new forum form
                    $requestURI = $_SERVER['REQUEST_URI'];
		            echo "<h2>New Forum</h2>";
                    echo "<form id='forum-post' action='postforum.php' method='POST' enctype='multipart/form-data'>";
                    echo "<table>";
                    echo "<tr>";
                    echo "<h4>";
                    echo "<td><label for='title'>Forum Title:</label></td>";
                    echo "<td><input type='text' name='title' id='title' maxlength='20'></td>";
                    echo "</h4>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<h4>";
                    echo "<td><label for='desc'>Description:</label></td>";
                    echo "<td><textarea name='desc' rows='10' cols='30' type='text' name='desc' id='desc' maxlength='150'></textarea></td>";
                    echo "</h4>";
                    echo "</tr>";
                    echo "<h4>";
                    echo "<td><input type='submit' value='Submit'></td>";
                    echo "<input type=\"hidden\" id=\"redirect\" name=\"redirect\" value=\"$requestURI\">";
                    echo "</h4>";
                    echo "</tr>";
                    echo "</table>";
                    echo "</form>";
                    echo "<br>";
                    // display the search users function
                    echo "<h2>Search</h2>";
                    echo "<form action='admin.php'>";
                    echo "<label for='term'>Search: </label>";
                    echo "<input type='text' id='text' name='text'>";
                    echo "<input type='submit' value='Search'>";
                    echo "</form>";
                    if(isset($_GET['text'])){
                        listUsersForAdmin($_GET['text']);
                    }else{
                        "Nothing to display";
                    }
                }else{
                    $output = "You're not supposed to be here... We're watching.";
                    exit($output);
                }
            ?>

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
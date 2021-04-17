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
			<h2>Search</h2>
            <form action='search.php'>
            <label for='type'>Type: </label>
            <select name='type' id='type'>
                <option value='forum'>Forum</option>
                <option value='thread'>Thread</option>
                <option value='user'>User</option>
            </select>
            <label for='term'>Search: </label>
            <input type='text' id='text' name='text'>
            <input type='submit' value="Search">
            </form>
            <br>

            <?php 
                if(isset($_GET['type']) && !isset($_GET['text'])){
                    $type = $_GET['type'];
                    listSearchResults($type, "");
                }else if(isset($_GET['type']) && isset($_GET['type'])){
                    $type = $_GET['type'];
                    $text = $_GET['text'];
                    listSearchResults($type, $text);
                }else{
                    "Nothing to display";
                }
            ?>

		</div>

		<div class="sidebar">
			<?php displayNews(); ?>
			<br>
			<?php displayRecentComments(); ?>
			<br>
			<?php displayHotThreads(); ?>
		</div>
		
	</div>

	<div class="footer">
		<h3>&copy; The Closed Door Society</h3>
	</div>

</body>

</html>
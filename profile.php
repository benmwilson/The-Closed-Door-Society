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

	<?php displayHeader() ?>
	
	<div class="main">


		<div class="content">

			<?php

			// Only display profile if signed in, else redirect to signup page

			if (isset($_GET['id']))
				$userID = $_GET['id'];
			else if (isset($_SESSION['userid']))
				$userID = $_SESSION['userid'];
			else {
				die();
			}
			userProfile($userID);
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
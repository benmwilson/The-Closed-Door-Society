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
			if (isset($_SESSION['userid']))
				$userID = $_SESSION['userid'];
			else {
				header("Location: main.php");
				exit();
			}
			?>

			<h2>Edit Profile</h2>
			<br>
			<h3>You MUST enter your password in both boxes when changing description and/or image!</h3>
			<br><br>
			<form id="update-form" action="updateuser.php" method="POST" enctype="multipart/form-data">

				<label for="oldpass">Old Password:</label>
				<input type="password" name="oldpass" id="oldpass" required>
				<br>
				<label for="newpass">New Password:</label>
				<input type="password" name="newpass" id="newpass">
				<br>
				<label for="desc">Profile Description:</label>
				<textarea id="desc" name="desc" rows="4" cols="40"></textarea>
				<br>
				<label for="userimg">User Image:</label>
				<input type="file" id="userimg" name="userimg" accept="image/png">
				<br>
				<input type="submit" value="Submit">
				<br>

				<?php
				if (isset($_GET['error'])) {
					echo "<h4 style='color:red'>" . $_GET['error'] . "</h4>";
				}
				?>
			</form>

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
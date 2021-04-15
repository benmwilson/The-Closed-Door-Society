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

		<?php
			// only display profile if signed in, else redirect to signup page


					if(isset($_GET['id']))
						$userID = $_GET['id'];
					else if(isset($_SESSION['userid']))
						$userID = $_SESSION['userid'];
					else {
						header("Location: signup.php");
						die();
					}

			userProfile($userID);

			// display edit profile section if it's your profile
			if($userID == $_SESSION['userid']){
				// display the form to change information
				echo "<div class='content'>";
            	echo "<h2>Edit Profile</h2>";
				echo "<br>";
            	echo "<form action='changePassword' method='POST' enctype='multipart/form-data'>";
                echo "<fieldset>";
				echo "<legend>Change your password</legend>";
				echo "<h4>";
                echo "<label for='oldpass'>Old Password:</label>";
                echo "<input type='text' name='oldpass' id='oldpass' maxlength='64' required>";
				echo "</h4>";
				echo "<h4>";
                echo "<label for='newpass'>New Password:</label>";
                echo "<input type='text' name='newpass' id='newpass' required>";
				echo "</h4>";
            	echo "<input type='submit' value='Submit'>";
				echo "</fieldset>";
				echo "</form>";
				echo "<br>";
				echo "<form action='changePicture' method='POST' enctype='multipart/form-data'>";
				echo "<fieldset>";
				echo "<legend>Change Profile Picture</legend>";
				echo "<h4>";
                echo "<label for='userimg'>User Image:</label>";
                echo "<input type='file' id='userimg' name='userimg' accept='image/png'>";
				echo "</h4>";
            	echo "<input type='submit' value='Submit'>";
				echo "</fieldset>";
				echo "</form>";
				echo "<br>";
				echo "<form action='changePicture' method='POST' enctype='multipart/form-data'>";
				echo "<fieldset>";
				echo "<legend>Update profile description</legend>";
				echo "<label for='desc'>Description</label>";
				echo "<input type='text' id='desc' name='desc'>";
				echo "<input type='submit' value='Submit'>";
	           	echo "</form>";
				echo "</div>";
			}
		?>

		</div>
		<div class="sidebar">
			<h2>Behind The Closed Doors</h2>
			<p>Always watching</p>
			<img src="img/logo.jpg" alt="" width="250" height="250">
		</div>

	</div>

	<div class="footer">
		<h3>&copy; The Closed Door Society</h3>
	</div>

</body>

</html>
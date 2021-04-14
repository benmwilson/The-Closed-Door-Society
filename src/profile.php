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


					// if(isset($_GET['id']))
					// 	$userID = $_GET['id'];
					// else if(isset($_SESSION['userid']))
					// 	$userID = $_SESSION['userid'];
					// else {
					// 	header("Location: signup.php");
					// 	die();
					// }
					userProfile($userID);
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
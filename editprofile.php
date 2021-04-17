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
            <form id="update-form" action="updateuser.php" method="POST" enctype="multipart/form-data">
                <table>
                    <tr>
                        <h4>
                            <td><label for="oldpass">Old Password:</label></td>
                            <td><input type="password" name="oldpass" id="oldpass" required></td>
                        </h4>
                    </tr>
                    <tr>
                        <h4>
                            <td><label for="newpass">New Password:</label></td>
                            <td><input type="password" name="newpass" id="newpass"></td>
                        </h4>
                    </tr>
                    <tr>
                        <h4>
                            <td><label for="desc">Profile Description:</label></td>
                            <td><textarea id="desc" name="desc" rows="4" cols="40"></textarea></td>
                        </h4>
                    </tr>
                    <tr>
                        <h4>
                            <td><label for="userimg">User Image:</label></td>
                            <td><input type="file" id="userimg" name="userimg" accept="image/png"></td>
                        </h4>
                    <tr>
                        <h4>
                            <td><input type="submit" value="Submit"></td>
                        </h4>
                    </tr>
                </table>
				<?php
					if(isset($_GET['error'])){
						echo "<h4 style='color:red'>".$_GET['error']."</h4>";
					}
				?>
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
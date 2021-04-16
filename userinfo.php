<?php
	
	include_once 'database.php';
	
	function notLoggedInHeader() {
		$requestURI = $_SERVER['REQUEST_URI'];
		echo "<form id = \"userinfo\" action=\"login.php\" method=\"POST\">";
		echo "<input type=\"text\" id=\"username\" name=\"username\" placeholder=\"Username\">";
		echo "<input type=\"password\" id=\"password\" name=\"password\" placeholder=\"Password\">";
		echo "<input type=\"submit\" value=\"Login\">";
		echo "<input type=\"hidden\" id=\"redirect\" name=\"redirect\" value=\"$requestURI\">";
		echo "<a id = \"signup\" href=\"signup.php\">Sign Up</a>";
		echo "</form>";
	}

	echo "<div class=\"right-header\">";
	
	if(isset($_SESSION['userid'])) {
		
		$user = getUserByID($_SESSION['userid']);
		
		if($_SESSION['passhash'] == $user[3]) {
			
			$userID = $user[0];
			$username = $user[1];
			
			echo "<a href=\"profile.php?id=$userID\">";
			echo "<img class=\"profile-pic\" src=\"img/profile_$userID.png\">";
			echo "</a>";
			echo "<h3>";
			echo "<a href=\"profile.php?id=$userID\">$username</a>";
			echo " - ";
			echo "<a href=\"logout.php\">Log Out</a>";
			echo "</h3>";
			
		} else {
			notLoggedInHeader();
		}
		
	} else {
		notLoggedInHeader();
	}
	
	echo "</div>";
	
?>
<?php
	
	include_once 'database.php';
	
	function header_no_user() {
		$requestURI = $_SERVER['REQUEST_URI'];
		echo "<form action=\"login.php\" method=\"POST\">";
		echo "<input type=\"text\" id=\"username\" name=\"username\" placeholder=\"Username\">";
		echo "<input type=\"password\" id=\"password\" name=\"password\" placeholder=\"Password\">";
		echo "<input type=\"submit\" value=\"Login\">";
		echo "<input type=\"hidden\" id=\"redirect\" name=\"redirect\" value=\"$requestURI\">";
		echo "<a href=\"signup.php\">Sign Up</a>";
		echo "</form>";
	}

	echo "<div class=\"right-header\">";
	
	if(isset($_SESSION['userid'])) {
		
		$user = get_user_by_id($_SESSION['userid']);
		
		if($_SESSION['passhash'] == $user[3]) {
			
			$userID = $user[0];
			$username = $user[1];
			
			echo "<a href=\"profile.php?id=$userID\">";
			echo "<img class=\"profile-pic\" src=\"img/$userID.png\">";
			echo "</a>";
			echo "<h3>";
			echo "<a href=\"profile.php?id=$userID\">$username</a>";
			echo " - ";
			echo "<a href=\"logout.php\">Log Out</a>";
			echo "</h3>";
			
		} else {
			header_no_user();
		}
		
	} else {
		header_no_user();
	}
	
	echo "</div>";
	
?>
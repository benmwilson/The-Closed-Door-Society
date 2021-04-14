<?php
	
	// DB Setup
	$dbuser = 'root';
	$dbpass = '';
	$dbname = 'forum';
	$db = new mysqli('localhost', $dbuser, $dbpass, $dbname);
	
	// Setup prepared statements
	$usernameQuery = $db->prepare("SELECT * FROM Users WHERE Username = ?;");
	$userIDQuery = $db->prepare("SELECT * FROM Users WHERE ID = ?;");
	$forumQuery = $db->prepare("SELECT * FROM Forums WHERE Parent = ? ORDER BY UpdateTime DESC;");
	$threadQueryByForum = $db->prepare("SELECT * FROM Threads WHERE ForumID = ? ORDER BY UpdateTime DESC;");
	$threadQueryByThread = $db->prepare("SELECT * FROM Threads WHERE ID = ?;");
	$commentQueryByThread = $db->prepare("SELECT * FROM Comments WHERE ThreadID = ? ORDER BY UpdateTime ASC LIMIT ?;");
	$commentQueryByUser = $db->prepare("SELECT * FROM Comments WHERE PosterID = ? ORDER BY UpdateTime DESC LIMIT ?;");
	//$userCommentQuery = $db->prepare("SELECT * FROM UserComments WHERE PosterID = ?;");
	
	$userInsert = $db->prepare("INSERT INTO Users(Username, Email, Password, Administrator) VALUE (?, ?, ?, FALSE);");
	$commentInsert = $db->prepare("INSERT INTO Comments(PosterID, ThreadID, UpdateTime, Content) VALUE (?, ?, NOW(), ?);");
	$threadInsert = $db->prepare("INSERT INTO Threads(ForumID, UpdateTime, Title) VALUE (?, NOW(), ?);");
	
	$threadUpdateTime = $db->prepare("UPDATE Threads SET UpdateTime = NOW() WHERE ID = ?;");
	$forumUpdateTime = $db->prepare("UPDATE Forums SET UpdateTime = NOW() WHERE ID = ?;");
	
	function get_user_by_username($username) {
		
		global $usernameQuery;
		
		$usernameQuery->bind_param("s", $username);
		$usernameQuery->execute();
		$result = $usernameQuery->get_result();
		
		if($user = $result->fetch_row()) {
			return $user;
		} else {
			return null;
		}
		
		$result->close();
		
	}
	
	function get_user_by_id($userID) {
		
		global $userIDQuery;
		
		$userIDQuery->bind_param("i", $userID);
		$userIDQuery->execute();
		$result = $userIDQuery->get_result();
		
		if($user = $result->fetch_row()) {
			return $user;
		} else {
			return null;
		}
		
		$result->close();
		
	}
	
	// Echo a list of subforums to this forum
	function forum_list($parentID) {
		
		global $forumQuery;
		
		$forumQuery->bind_param("i", $parentID);
		$forumQuery->execute();
		$forums = $forumQuery->get_result();
		
		if($forums->num_rows > 0) {
			echo "<h2>Forums</h2>";
		}
		
		while($forumRow = $forums->fetch_row()) {
			
			$forumID = $forumRow[0];
			$forumName = $forumRow[3];
			$forumDesc = $forumRow[4];
			
			echo "<div class=\"content-row\">";
			echo "<div class=\"post-title\">";
			echo "<h4><a href=\"forum.php?id=$forumID\">$forumName</a></h4>";
			echo "</div>";
			echo "<div class=\"post-preview\">";
			echo "<p>$forumDesc</p>";
			echo "</div>";
			echo "</div>";
			
		}
		
		$forums->close();
		
		//TODO: Administrator actions
		
	}
	
	// Echo a list of posts in this forum
	function thread_list($forumID) {
		
		global $threadQueryByForum, $commentQueryByThread;
		
		$threadQueryByForum->bind_param("i", $forumID);
		$threadQueryByForum->execute();
		$threads = $threadQueryByForum->get_result();
		
		$amount = 1;
		$commentQueryByThread->bind_param("ii", $threadID, $amount);
		
		echo "<h2>Threads</h2>";
		
		while($threadRow = $threads->fetch_row()) {
			
			$threadID = $threadRow[0];
			$threadTitle = $threadRow[3];
			
			$commentQueryByThread->execute();
			$comment = $commentQueryByThread->get_result()->fetch_row();
			$commentContent = $comment[4];
			
			echo "<div class=\"content-row\">";
			echo "<div class=\"post-title\">";
			echo "<h4><a href=\"thread.php?id=$threadID\">$threadTitle</a></h4>";
			echo "</div>";
			echo "<div class=\"post-preview\">";
			echo "<p>$commentContent</p>";
			echo "</div>";
			echo "</div>";
			
		}
		
		$threads->close();
		
		if(isset($_SESSION['userid'])) {
			
			$requestURI = $_SERVER['REQUEST_URI'];
			
			echo "<div class=\"content-row\">";
			echo "<form action=\"post.php\" method=\"POST\">";
			echo "<div class=\"label\">";
			echo "<label for=\"title\">Title: </label>";
			echo "<input type=\"text\" class=\"title\" id=\"title\" name=\"title\">";
			echo "</div>";
			echo "<textarea id=\"replytext\" name=\"replytext\" rows=\"4\" cols=\"40\"></textarea>";
			echo "<input type=\"submit\" class=\"reply\" value=\"Post\">";
			echo "<input type=\"hidden\" id=\"forumid\" name=\"forumid\" value=\"$forumID\">";
			echo "<input type=\"hidden\" id=\"redirect\" name=\"redirect\" value=\"$requestURI\">";
			echo "</form>";
			echo "</div>";
			
		}
		
	}
	
	function comment_list($threadID) {
		
		global $threadQueryByThread, $commentQueryByThread;
		
		$threadQueryByThread->bind_param("i", $threadID);
		$threadQueryByThread->execute();
		$thread = $threadQueryByThread->get_result();
		
		if($thread->num_rows == 0) {
			header("Location: main.php");
			die();
		} else {
			$threadTitle = $thread->fetch_row()[3];
		}
		
		$amount = 10;
		
		$commentQueryByThread->bind_param("ii", $threadID, $amount);
		$commentQueryByThread->execute();
		$comments = $commentQueryByThread->get_result();
		
		echo "<h2>$threadTitle</h2>";
		
		while($comment = $comments->fetch_row()) {
			
			$posterID = $comment[1];
			$posterName = get_user_by_id($posterID)[1];
			$postBody = $comment[4];
			
			echo "<div class=\"content-row\">";
			echo "<div class=\"profile-pic\">";
			echo "<a href=\"profile.php?id=$posterID\"><img class=\"profile-pic\" src=\"img/$posterID.png\"></a>";
			echo "<h3><a href=\"profile.php?id=$posterID\">$posterName</a></h3>";
			echo "</div>";
			echo "<div class=\"post-comment\">";
			echo "<p>$postBody</p>";
			echo "</div>";
			echo "</div>";
			
		}
		
		if(isset($_SESSION['userid'])) {
			
			$requestURI = $_SERVER['REQUEST_URI'];
			
			echo "<div class=\"content-row\">";
			echo "<form action=\"reply.php\" method=\"POST\">";
			echo "<textarea id=\"replytext\" name=\"replytext\" rows=\"4\" cols=\"40\"></textarea>";
			echo "<input type=\"submit\" class=\"reply\" value=\"Reply\">";
			echo "<input type=\"hidden\" id=\"threadid\" name=\"threadid\" value=\"$threadID\">";
			echo "<input type=\"hidden\" id=\"redirect\" name=\"redirect\" value=\"$requestURI\">";
			echo "</form>";
			echo "</div>";
			
		}
		
	}
	
	function user_profile($userID) {
		
		global $commentQueryByUser, $threadQueryByThread;
		
		$user = get_user_by_id($userID);
		$username = $user[1];
		
		echo "<h2>$username</h2>";
		echo "<div class=\"content-row\">";
		echo "<div class=\"profile-pic\">";
		echo "<img class=\"profile-pic-large\" src=\"img/$userID.png\">";
		echo "</div>";
		echo "<div class=\"profile-desc\">";
		echo "<p>TODO: User Description Here</p>"; //TODO: User Description
		echo "</div>";
		echo "</div>";
		
		$amount = 10;
		
		$commentQueryByUser->bind_param("ii", $user[0], $amount);
		$commentQueryByUser->execute();
		$comments = $commentQueryByUser->get_result();
		
		if($comments->num_rows > 0) {
			echo "<h2>Activity</h2>";
		}
		
		while($comment = $comments->fetch_row()) {
			
			$threadQueryByThread->bind_param("i", $comment[2]);
			$threadQueryByThread->execute();
			$thread = $threadQueryByThread->get_result()->fetch_row();
			
			$threadID = $thread[0];
			$threadTitle = $thread[3];
			$commentContent = $comment[4];
			
			echo "<div class=\"content-row\">";
			echo "<div class=\"post-title\">";
			echo "<h4><a href=\"thread.php?id=$threadID\">$threadTitle</a></h4>";
			echo "</div>";
			echo "<div class=\"post-preview\">";
			echo "<p>$commentContent</p>";
			echo "</div>";
			echo "</div>";
			
		}
		
	}
	
	function insert_user($username, $password, $email) {
		
		global $userInsert;
		
		$password = password_hash($password, PASSWORD_DEFAULT);
		
		$userInsert->bind_param("sss", $username, $email, $password);
		$userInsert->execute();
		
		echo $userInsert->affected_rows;
		
		if($userInsert->affected_rows > 0) {
			//$result = $userInsert->get_result();
			session_start();
			$_SESSION['userid'] = $userInsert->insert_id;
			$_SESSION['passhash'] = $password;
		}
		
		return $userInsert->affected_rows;
		
	}
	
	function insert_comment($posterID, $threadID, $content) {
		
		global $commentInsert, $threadUpdateTime, $forumUpdateTime, $threadQueryByThread;
		
		// Some modest input sanitation
		$content = strip_tags($content);
		$content = str_replace("\n", "<br>", $content);
		
		$commentInsert->bind_param("iis", $posterID, $threadID, $content);
		$commentInsert->execute();
		
		$threadQueryByThread->bind_param("i", $threadID);
		$threadQueryByThread->execute();
		$thread = $threadQueryByThread->get_result()->fetch_row();
		$forumID = $thread[1];
		
		$threadUpdateTime->bind_param("i", $threadID);
		$forumUpdateTime->bind_param("i", $forumID);
		$threadUpdateTime->execute();
		$forumUpdateTime->execute();
		
		
	}
	
	function insert_thread($posterID, $forumID, $title, $content) {
		
		global $threadInsert, $commentInsert, $forumUpdateTime;
		
		// Some modest input sanitation
		$title = strip_tags($title);
		$content = strip_tags($content);
		$content = str_replace("\n", "<br>", $content);
		
		$threadInsert->bind_param("is", $forumID, $title);
		$threadInsert->execute();
		
		$threadID = $threadInsert->insert_id;
		
		$commentInsert->bind_param("iis", $posterID, $threadID, $content);
		$commentInsert->execute();
		
		$forumUpdateTime->bind_param("i", $forumID);
		$forumUpdateTime->execute();
		
	}
	
?>
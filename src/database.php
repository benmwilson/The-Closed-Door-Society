<?php

// DB Setup

$dbuser = 'root';
$dbpass = '';
$dbname = 'forum';
$db = new mysqli('localhost', $dbuser, $dbpass, $dbname);

// Setup prepared statements

// User related
$usernameQuery = $db->prepare("SELECT * FROM Users WHERE Username = ?;");
$userIDQuery = $db->prepare("SELECT * FROM Users WHERE ID = ?;");
$userInsert = $db->prepare("INSERT INTO Users(Username, Email, Password, Administrator) VALUE (?, ?, ?, FALSE);");

// Forum related
$forumQuery = $db->prepare("SELECT * FROM Forums WHERE Parent = ? ORDER BY UpdateTime DESC;");
$forumUpdateTime = $db->prepare("UPDATE Forums SET UpdateTime = NOW() WHERE ID = ?;");

// Thread related
$threadQueryByForum = $db->prepare("SELECT * FROM Threads WHERE ForumID = ? ORDER BY UpdateTime DESC;");
$threadQueryByThread = $db->prepare("SELECT * FROM Threads WHERE ID = ?;");
$threadInsert = $db->prepare("INSERT INTO Threads(ForumID, UpdateTime, Title) VALUE (?, NOW(), ?);");
$threadUpdateTime = $db->prepare("UPDATE Threads SET UpdateTime = NOW() WHERE ID = ?;");

// Comment related
$commentQueryByThread = $db->prepare("SELECT * FROM Comments WHERE ThreadID = ? ORDER BY UpdateTime ASC LIMIT ?;");
$commentQueryByUser = $db->prepare("SELECT * FROM Comments WHERE PosterID = ? ORDER BY UpdateTime DESC LIMIT ?;");
$commentInsert = $db->prepare("INSERT INTO Comments(PosterID, ThreadID, UpdateTime, Content) VALUE (?, ?, NOW(), ?);");



// Functions to do shit and display shit

// Get user by username

function getUserByUsername($username)
{

	global $usernameQuery;

	$usernameQuery->bind_param("s", $username);
	$usernameQuery->execute();
	$result = $usernameQuery->get_result();

	if ($user = $result->fetch_row()) {
		return $user;
	} else {
		return null;
	}

	$result->close();
}

// Get user by ID

function getUserByID($userID)
{

	global $userIDQuery;

	$userIDQuery->bind_param("i", $userID);
	$userIDQuery->execute();
	$result = $userIDQuery->get_result();

	if ($user = $result->fetch_row()) {
		return $user;
	} else {
		return null;
	}

	$result->close();
}

// Echo a list of subforums to this forum

function listForums($parentID)
{

	global $forumQuery;

	$forumQuery->bind_param("i", $parentID);
	$forumQuery->execute();
	$forums = $forumQuery->get_result();

	if ($forums->num_rows > 0) {
		echo "<h2>Forums</h2>";
	}

	while ($forumRow = $forums->fetch_row()) {

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
}

// Echo a list of threads in this forum

function listThreads($forumID)
{

	global $threadQueryByForum, $commentQueryByThread;

	$threadQueryByForum->bind_param("i", $forumID);
	$threadQueryByForum->execute();
	$threads = $threadQueryByForum->get_result();

	$amount = 1;
	$commentQueryByThread->bind_param("ii", $threadID, $amount);

	echo "<h2>Threads</h2>";

	while ($threadRow = $threads->fetch_row()) {

		$threadID = $threadRow[0];
		$threadTitle = $threadRow[3];


		echo "<div class=\"content-row\">";
		echo "<div class=\"post-title\">";
		echo "<h4><a href=\"thread.php?id=$threadID\">$threadTitle</a></h4>";
		echo "</div>";
		echo "</div>";
	}

	$threads->close();

	if (isset($_SESSION['userid'])) {

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

// Echo the user profile 

function userProfile($userID)
{

	global $commentQueryByUser, $threadQueryByThread;

	$user = getUserByID($userID);
	$username = $user[1];

	echo "<h2>$username's Profile</h2>";
	echo "<div class=\"content-row\">";
	echo "<div class=\"profile-pic\">";
	echo "<img class=\"profile-pic-large\" src=\"img/$userID.png\">";
	echo "</div>";
	echo "<div class=\"profile-desc\">";
	echo "<p>User Description Here</p>";
	echo "</div>";
	echo "</div>";

	// amount of comments to display on user's profile
	$amount = 10;

	$commentQueryByUser->bind_param("ii", $user[0], $amount);
	$commentQueryByUser->execute();
	$comments = $commentQueryByUser->get_result();

	echo "<h2>$username's Post Activity</h2>";
	echo "<div class=\"content-row\">";
	echo "<div class=\"post-title\">";


	while ($comment = $comments->fetch_row()) {

		$threadQueryByThread->bind_param("i", $comment[2]);
		$threadQueryByThread->execute();
		$thread = $threadQueryByThread->get_result()->fetch_row();

		$threadID = $thread[0];
		$threadTitle = $thread[3];
		$commentContent = $comment[4];

		echo "<h4><a href=\"thread.php?id=$threadID\">$threadTitle</a></h4>";
		echo "</div>";
		echo "<div class=\"post-preview\">";
		echo "<p>$commentContent</p>";
	}

	echo "</div>";
	echo "</div>";
}

// Echo a list of comments in this thread

function listComments($threadID)
{
}


// Insert a user into the DB

function insertUser($username, $password, $email)
{
}

// Insert a thread into the DB

function insertThread($posterID, $forumID, $title, $content)
{
}

// Insert a comment into the DB

function insertComment($posterID, $threadID, $content)
{
}

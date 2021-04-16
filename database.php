<?php

include_once 'render.php';

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
$usernameQueryyy = $db->prepare("SELECT * FROM Users WHERE Username = ?;");
$getAllUsers = $db->prepare("SELECT * FROM Users;");

// Image related
$imgInsert = $db->prepare("INSERT INTO Images(Image) VALUE (?);");
$displayImg = $db->prepare("SELECT Image FROM Images ORDER BY");

// News related
$insertNews = $db->prepare("INSERT INTO News(UpdateTime, Title, Content) VALUE ( NOW(), ?, ?);");
$getNews = $db->prepare("SELECT * FROM News ORDER BY ID DESC LIMIT 1;");


// Forum related
$forumQuery = $db->prepare("SELECT * FROM Forums WHERE Parent = ? ORDER BY UpdateTime DESC;");
$forumIDQuery = $db->prepare("SELECT * FROM Forums WHERE ID = ?");
$forumUpdateTime = $db->prepare("UPDATE Forums SET UpdateTime = NOW() WHERE ID = ?;");
$forumInsert = $db->prepare("INSERT INTO Forums(Parent, UpdateTime, Name, Description) VALUE (1, NOW(), ?, ?);");

// Thread related
$threadQueryByForum = $db->prepare("SELECT * FROM Threads WHERE ForumID = ? ORDER BY UpdateTime DESC;");
$threadQueryByThread = $db->prepare("SELECT * FROM Threads WHERE ID = ?;");
$threadInsert = $db->prepare("INSERT INTO Threads(ForumID, UpdateTime, Title) VALUE (?, NOW(), ?);");
$threadUpdateTime = $db->prepare("UPDATE Threads SET UpdateTime = NOW() WHERE ID = ?;");

// Comment related
$commentQueryByThread = $db->prepare("SELECT * FROM Comments WHERE ThreadID = ? ORDER BY UpdateTime ASC LIMIT ?;");
$commentQueryByUser = $db->prepare("SELECT * FROM Comments WHERE PosterID = ? ORDER BY UpdateTime DESC LIMIT ?;");
$commentInsert = $db->prepare("INSERT INTO Comments(PosterID, ThreadID, UpdateTime, Content) VALUE (?, ?, NOW(), ?);");

// Like related
$increaseLikeOnComment = $db->prepare("UPDATE Comments SET Likes = Likes + 1 WHERE id = ?");
$decreaseLikeOnComment = $db->prepare("UPDATE Comments SET Likes = Likes - 1 WHERE id = ?");
$addLikeToDB = $db->prepare("INSERT INTO Likes(UpdateTime, UserID, CommentID) VALUE (NOW(), ?, ?);");
$removeLikeFromDB = $db->prepare("DELETE FROM Likes WHERE UserID=? AND CommentID=?;");
$hasLikedQuery = $db->prepare("SELECT * FROM Likes WHERE UserID=? AND CommentID=?;");
$getLikesOfComment = $db->prepare("SELECT likes FROM Comments WHERE ID = ? ;");

// Recent comment related
$getRecentComments = $db->prepare("SELECT * FROM Comments ORDER BY ID DESC LIMIT 5;");

// Hot thread related
$getHotComments = $db->prepare("SELECT * FROM Comments ORDER BY Likes DESC LIMIT 5;");

// Search queries by name
$getForumsBySearch = $db->prepare("SELECT * FROM Forums WHERE Name LIKE ?;");
$getThreadsBySearch = $db->prepare("SELECT * FROM threads WHERE Title LIKE ?;");
$getUsersBySearch = $db->prepare("SELECT * FROM Users WHERE Username LIKE ?;");

// Functions to do shit and display shit

function listSearchResults($type, $query){

	$search = "%$query%";
	global $getThreadsBySearch;
	global $getForumsBySearch;
	global $getUsersBySearch;

	if($type == 'forum'){
		$getForumsBySearch->bind_param("s", $search);
		$getForumsBySearch->execute();
		$results = mysqli_stmt_get_result($getForumsBySearch);

		if ($results) {
			echo "<h2>Results</h2>";
		}else{
			echo "No results to show...";
		}

		while ($resultsRow = mysqli_fetch_array($results)){
			$forumID = $resultsRow[0];
			$forumName = $resultsRow[3];
			$forumDesc = $resultsRow[4];

			echo "<div class=\"content-row\">";
			echo "<div class=\"post-title\">";
			echo "<h4><a href=\"forum.php?id=$forumID\">$forumName</a></h4>";
			echo "</div>";
			echo "<div class=\"post-preview\">";
			echo "<p>$forumDesc</p>";
			echo "</div>";
			echo "</div>";
		}
		$results->close();
	}else if($type == 'thread'){
		$getThreadsBySearch->bind_param("s", $search);
		$getThreadsBySearch->execute();
		$results = mysqli_stmt_get_result($getThreadsBySearch);

		if ($results) {
			echo "<h2>Results</h2>";
		}else{
			echo "No results to show...";
		}

		while ($resultsRow = mysqli_fetch_array($results)){
			$threadID = $resultsRow[0];
			$threadTitle = $resultsRow[3];
			$forumId = $resultsRow[1];
			$forum = getForumByID($forumId);

			echo "<div class=\"content-row\">";
			echo "<div class=\"post-title\">";
			echo "<h4><a href=\"thread.php?id=$threadID\">$threadTitle</a></h4>";
			echo "</div>";
			echo "<div class=\"post-preview\">";
			echo "<p>From forum: ".$forum[3]."</p>";
			echo "</div>";
			echo "</div>";
		}
		$results->close();
	}else if($type == 'user'){
		$getUsersBySearch->bind_param("s", $search);
		$getUsersBySearch->execute();
		$results = mysqli_stmt_get_result($getUsersBySearch);

		if ($results) {
			echo "<h2>Results</h2>";
		}else{
			echo "No results to show...";
		}

		while ($resultsRow = mysqli_fetch_array($results)){
			$userId = $resultsRow[0];
			$userName = $resultsRow[1];
			$userDesc = $resultsRow[4];

			echo "<div class=\"content-row\">";
			echo "<div class=\"post-title\">";
			echo "<h4><a href=\"profile.php?id=$userId\">$userName</a></h4>";
			echo "</div>";
			echo "<div class=\"post-preview\">";
			echo "<p>$userDesc</p>";
			echo "</div>";
			echo "</div>";
		}
		$results->close();
	}else{
		$output = "Unable to comprehend the search parameters";
		exit($output);
	}
}

// returns if the logged in user is an admin
function isAdmin(){
	// check if the session for user id is stored (logged in)
	if(isset($_SESSION['userid'])){
		// store the variable for user id
		$id = $_SESSION['userid'];
		// pull user from that id
		$user = getUserByID($id);

		// check if the user is an admin $user[5] == 1;
		if($user[5] == 1){
			// user is an admin
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

// function to list all users for an admin
function listUsersForAdmin($query){
	
	$search = "%$query%";
	global $getUsersBySearch;

	$getUsersBySearch->bind_param("s", $search);
	$getUsersBySearch->execute();
	$results = mysqli_stmt_get_result($getUsersBySearch);
	if ($results) {
	}else{
		echo "<h2>No results to show...</2>";
	}

	while ($resultsRow = mysqli_fetch_array($results)){
		$userId = $resultsRow[0];
		$userName = $resultsRow[1];
		$userDesc = $resultsRow[4];

		echo "<div class=\"content-row\">";
		echo "<div class=\"post-title\">";
		echo "<h4><a href=\"profile.php?id=$userId\">$userName</a></h4>";
		echo "</div>";
		echo "<div class=\"post-preview\">";
		echo "<p>Description: $userDesc</p>";
		echo "</div>";
		echo "<div>";
		echo "<a href='' style='color:red'>BAN</a>";
		echo "</div>";
		echo "</div>";
	}
	$results->close();
}

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

function getForumByID($forumID)
{
	global $forumIDQuery;

	$forumIDQuery->bind_param("i", $forumID);
	$forumIDQuery->execute();
	$result = $forumIDQuery->get_result();

	if ($forum = $result->fetch_row()) {
		return $forum;
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
		echo "<h2>New Thread</h2>";
		echo "<label for=\"title\">Title: </label>";
		echo "<input type=\"text\" class=\"title\" id=\"title\" name=\"title\">";
		echo "</div>";
		echo "<label>Content</label>";
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

	// If profile not found

	if ($user == null) {
		echo "<h2>No Profile Found!</h2>";
		echo "<br>";
		echo "<h3 style='text-align:center'>We could not find a user with that userID :(</h3>";
	} else {

		// Display profile with selected ID

		$username = $user[1];

		echo "<h2>$username's Profile</h2>";
		echo "<div class=\"content-row\">";
		echo "<div class=\"profile-pic\">";
		echo "<img class=\"profile-pic-large\" src=\"img/profile_$userID.png\">";
		echo "</div>";
		echo "<div class=\"profile-desc\">";
		echo "<p>User Description Here</p>";
		echo "</div>";
		echo "</div>";

		// Amount of comments to display on user's profile

		$amount = 10;

		$commentQueryByUser->bind_param("ii", $user[0], $amount);
		$commentQueryByUser->execute();
		$comments = $commentQueryByUser->get_result();

		echo "<h2>$username's Post Activity</h2>";


		while ($comment = $comments->fetch_row()) {

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
}

// Echo a list of comments in this thread

function listComments($threadID)
{
	global $threadQueryByThread, $commentQueryByThread;

	$parsedThread = intval($threadID);
	$amount = 3;
	$threadQueryByThread->bind_param("i", $threadID);
	$threadQueryByThread->execute();
	$thread = $threadQueryByThread->get_result();

	if ($thread->num_rows == 0) {
		header("Location: main.php");
		die();
	} else {
		$threadTitle = $thread->fetch_row()[3];
	}

	$commentQueryByThread->bind_param("ii", $parsedThread, $amount);
	$commentQueryByThread->execute();
	$comments = $commentQueryByThread->get_result();

	echo "<h2>$threadTitle</h2>";

	while ($commentsRow = $comments->fetch_row()) {

		$posterID = $commentsRow[1];
		$commentId = $commentsRow[0];
		$commentPosterId = $commentsRow[1];
		$commentTime = $commentsRow[3];
		$commentData = $commentsRow[4];

		$poster = getUserByID($commentPosterId);
		$username = $poster[1];

		echo "<br>";
		echo "<fieldset class='comment'>";
		echo "<a href=\"profile.php?id=$posterID\"><img class=\"profile-pic\" src=\"img/profile_$posterID.png\"></a>";
		echo "<br>";
		displayLikeBar($commentId);
		echo "<br>";

		echo "<legend>[$username at $commentTime]</legend>";
		echo "<p>$commentData</p>";
		echo "</fieldset>";
	}

	if (isset($_SESSION['userid'])) {

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

	$comments->close();
}

// Insert a user into the DB

function insertUser($username, $password, $email)
{
	global $userInsert;

	$password = password_hash($password, PASSWORD_DEFAULT);

	$userInsert->bind_param("sss", $username, $email, $password);
	$userInsert->execute();

	echo $userInsert->affected_rows;

	if ($userInsert->affected_rows > 0) {
		//$result = $userInsert->get_result();
		session_start();
		$_SESSION['userid'] = $userInsert->insert_id;
		$_SESSION['passhash'] = $password;
	}

	return $userInsert->affected_rows;
}

// Insert a thread into the DB

function insertThread($posterID, $forumID, $title, $content)
{

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

function insertForum($forumName, $forumDesc){
	global $forumInsert;

	// Some modest input sanitation
	$title = strip_tags($forumName);
	$forumDesc = strip_tags($forumDesc);

	$forumInsert->bind_param("ss", $title, $forumDesc);
	$forumInsert->execute();
}

// Insert a comment into the DB

function insertComment($posterID, $threadID, $content)
{

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

// // Insert an Image into the DB

// function imgInsert($file)
// {
// 	global $imgInsert;

// 	$imgInsert->bind_param("b", $file);
// 	if ($imgInsert->execute()) {
// 		echo '<script>alert("Image Inserted into DB!")</script>';
// 	} else {
// 		echo '<script>alert("Image Insert Failed!")</script>';
// 	}
// }

// function displayImg($imgID)
// {

// 	global $displayImg;

// 	$displayImg->bind_param("i", $imgID);

// 	$img = $displayImg->get_result();

// 	echo '<img src="data:image/jpeg;base64,' . base64_encode($img) . '"/>  ';
// }

function addNews($title, $content)
{
	global $insertNews, $getNews;

	$insertNews->bind_param("ss", $title, $content);
	$insertNews->execute();

	$getNews->execute();
	$news = $getNews->get_result()->fetch_row();

	$newsID = $news[0];


	return $newsID;
}

function displayNews()
{

	global $getNews;

	$getNews->execute();
	$news = $getNews->get_result();

	echo "<h2>Behind The Closed Doors</h2>";
	echo '<br>';

	while ($newsRow = $news->fetch_row()) {

		//$newsID = $newsRow[0];
		$newsTitle = $newsRow[2];
		$newsContent = $newsRow[3];
		$newsID = $newsRow[0];
		$newsTime = $newsRow[1];
		//$newsImg = $newsRow[4];

		echo "<h3>$newsTitle posted at ". substr($newsTime, 10, 6);"</h3>";
		echo "<p>$newsContent</p>";
		echo '<br>';
		echo "<img width='200' height='200' src=\"img/news_$newsID.png\">";
		echo '<br>';
	}
}

function displayRecentComments()
{

	global $getRecentComments;

	$getRecentComments->execute();
	$recentComments = $getRecentComments->get_result();

	echo "<h2>Recent Activity</h2>";
	echo '<br>';
	echo "<table>";
	echo "<tr>";
	echo '<th>Poster</th>';
	echo '<th>Content</th>';
	echo '<th>Time</th>';
	echo "</tr>";

	while ($recentCommentsRow = $recentComments->fetch_row()) {

		$poster = getUserByID($recentCommentsRow[1]);
		$username = $poster[1];
		$commentContent = $recentCommentsRow[4];
		$dateTime = substr($recentCommentsRow[3], 10, 6);

		echo "<tr>";
		echo "<td>$username</td>";
		echo "<td style='word-break:break-all;'>$commentContent</td>";
		echo "<td>$dateTime</td>";
		echo "</tr>";
	}
	echo "</table>";
}

function displayHotComments()
{

	global $getHotComments;

	$getHotComments->execute();
	$hotComments = $getHotComments->get_result();

	echo "<h2>Hot Comments</h2>";
	echo '<br>';
	echo "<table>";
	echo "<tr>";
	echo '<th>Poster</th>';
	echo '<th>Content</th>';
	echo '<th>Likes</th>';
	echo "</tr>";

	while ($hotCommentsRow = $hotComments->fetch_row()) {

		$poster = getUserByID($hotCommentsRow[1]);
		$username = $poster[1];
		$commentContent = $hotCommentsRow[4];
		$likes = $hotCommentsRow[5];

		echo "<tr>";
		echo "<td>$username</td>";
		echo "<td style='word-break:break-all;'>$commentContent</td>";
		echo "<td>$likes</td>";
		echo "</tr>";
	}
	echo "</table>";
}

function displayLikeBar($commentID)
{
	global $hasLikedQuery;


	if (isset($_SESSION['userid'])) {

		$userID = $_SESSION['userid'];


		$hasLikedQuery->bind_param("ii", $userID, $commentID);
		$hasLikedQuery->execute();

		$hasLiked = $hasLikedQuery->get_result();



		// If they have, show red heart
		if ($hasLiked->num_rows > 0) {
			//echo '<a onclick="increaseLike($commentID, $userID);"><img src= "img\Heart-icon.png" width="20" height="20></a>';

			echo "<a href='#' onclick='decreaseLike($commentID, $userID);''><img src='img\Heart-icon.png' width='20' height='20'></a>";

			// echo '<img src="img\Heart-icon.png" width="20" height="20">';
			getLikes($commentID);
		} else {
			// If they haven't show empty heart
			echo '<img src="img\heart-outline.png" width="20" height="20">';
			getLikes($commentID);
		}
	} else {
		getLikes($commentID);
	}
}

function getLikes($commentID)
{
	global $getLikesOfComment;

	$getLikesOfComment->bind_param("i", $commentID);
	$getLikesOfComment->execute();
	$likes = $getLikesOfComment->get_result()->fetch_row();

	echo "<p>Likes: $likes[0]</p>";
}

function increaseLike($commentID, $userID)
{

	global $addLikeToDB, $increaseLikeOnComment;

	$addLikeToDB->bind_param("ii", $userID, $commentID);
	$addLikeToDB->execute();

	$increaseLikeOnComment->bind_param("i", $commentID);
	$increaseLikeOnComment->execute();
}

function decreaseLike($commentID, $userID)
{

	global $removeLikeFromDB, $decreaseLikeOnComment;

	$removeLikeFromDB->bind_param("ii", $userID, $commentID);
	$removeLikeFromDB->execute();

	$decreaseLikeOnComment->bind_param("i", $commentID);
	$decreaseLikeOnComment->execute();
}

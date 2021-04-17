<!-- <?php

// Code from the comment liking system I was working on, couldn't get it done in time :( -BW

include_once 'database.php';

// global $type, $CommentID, $UserID;

// // $type = $_POST['type'];
// // $CommentID = $_POST['commentID'];
// $userID = $_POST['userID'];


// if ($type == 'like') {
//     increaseLike($CommentID, $UserID);
// } else {
//     decreaseLike($CommentID, $UserID);
// }

function hasLiked($userID, $commentID)
{

    echo "<p>$userID $commentID</p>";
    echo "<img src='img\Heart-icon.png' width='20' height='20'>";
    echo "<button id='unlike' type='button' data-CommentID='$commentID' data-UserID='$userID' onclick='unlike()'>Unlike</button>";
}

function hasntLiked($userID, $commentID)
{
    echo "<p>$userID $commentID</p>";
    echo '<img src="img\heart-outline.png" width="20" height="20">';
    echo "<button id='like' type='button' data-CommentID='$commentID' data-UserID='$userID' onclick='like()'>Like</button>";
}


?> -->
<?php

include_once 'database.php';



if (strlen($_POST['title']) <= 20) {
	$newsTitle = $_POST['title'];
} else {
	//error
	die();
}

if (strlen($_POST['content']) <= 150) {
	$newsContent = $_POST['content'];
} else {
	//error
	die();
}

$newsImg = $_FILES['newsimg'];
if ($newsImg['size'] > 10000000) {
	// error
	die();
}

if ($newsImg['type'] != "image/png") {
	//error
	die();
}

$newsID = addNews($newsTitle, $newsContent);

if ($newsID > 0) {
	$fileToMove = $newsImg['tmp_name'];
	move_uploaded_file($fileToMove, "img/news_$newsID.png");
	header("Location: main.php");
	die();
} else {
	//error
	die();
}

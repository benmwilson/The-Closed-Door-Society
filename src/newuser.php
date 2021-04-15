<?php
	
	include_once 'database.php';
	
	if(strlen($_POST['username']) <= 64) {
		$username = $_POST['username'];
	} else {
		header("Location: signup.php?error=1");
		die();
	}
	
	// password_hash() will take care of length
	$password = $_POST['password'];
	
	if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$email = $_POST['email'];
	} else {
		header("Location: signup.php?error=2");
		die();
	}
	
	$userImg = $_FILES['userimg'];
	if($userImg['size'] > 10000000) {
		header("Location: signup.php?error=3");
		die();
	}
	if($userImg['type'] != "image/png") {
		header("Location: signup.php?error=4");
		die();
	}
	
	$rval = insertUser($username, $password, $email);
	
	if($rval > 0) {
		$userID = $_SESSION['userid'];
		$fileToMove = $userImg['tmp_name'];
		move_uploaded_file($fileToMove, "img/$userID.png");
		header("Location: profile.php?ID=$userID");
		die();
	} else {
		header("Location: signup.php?error=$rval");
		die();
	}

?>
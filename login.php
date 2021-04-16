<?php

	include_once 'database.php';
	
	$user = getUserByUsername($_POST['username']);
	
	if(password_verify($_POST['password'], $user[3])) {
		session_start();
		$_SESSION['userid'] = $user[0];
		$_SESSION['passhash'] = $user[3];
		echo "Login Success";
	}
	
	$redirectURI = $_POST['redirect'];
	header("Location: $redirectURI");
	die();

?>
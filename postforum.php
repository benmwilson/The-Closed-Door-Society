<?php

    include 'database.php';

	session_start();
	
	if(!isAdmin()) {
		$redirectURI = $_POST['redirect'];
		header("Location: $redirectURI");
		die();
	}

	insertForum($_POST['title'], $_POST['desc']);

	$redirectURI = $_POST['redirect'];
	header("Location: $redirectURI");
	die();

?>
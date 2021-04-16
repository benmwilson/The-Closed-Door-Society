<?php

	session_start();
	
	if(!isset($_SESSION['userid'])) {
		$redirectURI = $_POST['redirect'];
		header("Location: $redirectURI");
		die();
	}
	
	include 'database.php';
	
	insertComment($_SESSION['userid'], $_POST['threadid'], $_POST['replytext']);
	
	$redirectURI = $_POST['redirect'];
	header("Location: $redirectURI");
	die();

?>
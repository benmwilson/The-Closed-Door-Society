<?php
	
	session_start();
	session_unset();
	session_destroy();
	
	$redirectURI = $_POST['redirect'];
	header("Location: main.php");
	die();

?>
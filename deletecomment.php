<?php
    include 'database.php';

    $redirect = $_SERVER['HTTP_REFERER'];

    deleteComment($_GET['id']);
    header("Location: $redirect");
    die();
?>
<?php
    include 'database.php';

    $redirect = $_SERVER['HTTP_REFERER'];

    deleteThread($_GET['id']);
    header("Location: $redirect");
    die();
?>
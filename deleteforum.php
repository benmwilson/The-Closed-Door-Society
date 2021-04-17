<?php
    session_start();
    include 'database.php';

    if(isset($_SERVER['HTTP_REFERER'])){
        $redirect = $_SERVER['HTTP_REFERER'];
    }else{
        $redirect = "main.php";
    }
    if(isAdmin()){
        if($_GET['id'] != 1){
            deleteForum($_GET['id']);
            header("Location: $redirect");
            die();
        }
    }else{
        header("Location: $redirect");
        die();
    }
?>
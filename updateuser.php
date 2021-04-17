<?php

include_once 'database.php';
session_start();


if(isset($_POST['oldpass']) && isset($_POST['newpass'])){   
    $oldPass = $_POST['oldpass'];
    $newPass = $_POST['newpass'];
    $user = getUserByID($_SESSION['userid']);

    // check to see if the old password matches the current password
    if($_SESSION['passhash']){
        if(password_verify($oldPass, $user[3])){
            // the inputted password is the same as the hashed user password, proceed to update
            if(isset($_POST['desc'])){
                $desc = $_POST['desc'];
            }else{
                $desc = $user[4];
            }

            updateProfile($user[0], $desc, $newPass);
            $newUser = getUserByID($user[0]);

            $_SESSION['userid'] = $newUser[0];
		    $_SESSION['passhash'] = $newUser[3];

            header("Location: main.php");
	        die();
        }else{
            $output = "Unable to verify passwords";
            header("Location: editprofile.php?error=$output");
            die();
        }
    }else{
        $output = "Passhash wasn't set, problem with your cookie settings?";
        exit($output);
    }
}else{
    $output = "you didn't have the right credentials to edit your profile, try again";
    exit($output);
}

?>
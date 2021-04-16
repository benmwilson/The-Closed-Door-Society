<!-- ALL MULTI-USE RENDER FUNCTIONS THAT DON'T NECESSARILY DRAW
    ON THE SQL TOO HARD SHOULD GO HERE
-->

<?php


include_once 'database.php';


function displayThreadTitle($id)
{
    $forum = getForumByID($id);
    // output the title as header
    echo "<h2 id='titleText'><a id='titleText' href='" . $_SERVER['HTTP_REFERER'] . "'><<<   </a>" . $forum[3] . "</h2>";
    echo "<br>";
}



?>
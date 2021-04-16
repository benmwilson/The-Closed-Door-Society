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

function displayHeader(){
    echo "<div class='header'>";
    echo "<div class='left-header'>";
    echo "<h1>The Closed Door Society</h1>";
    echo "<nav>";
    echo "<a class='header-nav' href='main.php'>Home</a>";
    echo "<a class='header-nav' href='search.php'>Search</a>";
    if(isAdmin()){
        echo "<a class='header-nav' href='admin.php'>Admin</a>";
    }
    echo "</nav>";
    echo "</div>";
    include 'userinfo.php';
    echo "</div>";
}
?>
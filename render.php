<!-- ALL MULTI-USE RENDER FUNCTIONS THAT DON'T NECESSARILY DRAW
    ON THE SQL TOO HARD SHOULD GO HERE
-->

<?php


include_once 'database.php';


function displayThreadTitle($id)
{
    $forum = getForumByID($id);
    if($forum){
    // output the title as header
    echo "<h2 id='titleText'><a id='titleText' href='" . $_SERVER['HTTP_REFERER'] . "'><<<   </a>" . $forum[3] . "</h2>";
    echo "<div class=\"content-row\">";
    echo "<div class='content-preview'>";
    echo "<h4>".$forum[4]."</h4>";
    echo "</div>";
    echo "</div>";
    }else{
        $output = "Unable to find post";
        exit($output);
    }
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
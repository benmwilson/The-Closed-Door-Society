<!-- ALL MULTI-USE RENDER FUNCTIONS THAT DON'T NECESSARILY DRAW
    ON THE SQL TOO HARD SHOULD GO HERE
-->

<?php

function displayHeader(){
    echo "<div class='header'>";
	echo "<div class='left-header'>";
	echo "<h1>The Closed Door Society</h1>";
	echo "<nav>";
	echo "<a class='header-nav' href='main.php'>Home</a>";
	echo "</nav>";
	echo "</div>";
	echo "<?php include 'userinfo.php'; ?>";
}

function displayThreadTitle($id, $redirect){
    $forum = getForumByID($id);
    // output the title as header
	echo "<h2 id='titleText'><a id='titleText' href='".$redirect."'><<<   </a>".$forum[3]."</h2>";
    echo "<br>";
}

?>
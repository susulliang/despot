<div class="unselectable">
<?php
	// Get current page number for posts and comments
	if($_GET["page"]){
	    $postPageNum = $_GET["page"];
	} else {
	    $postPageNum = 1;
	}

	// Also get page number for comments
	if($_GET["cmtpage"]){
		$cmtPageNum = $_GET["cmtpage"];
	} else {
		$cmtPageNum = 1;
	}

	// Set no userID for personal page and admin page
	if(!$userID) $userID="*";

	// Print the posts
	$isNextPage = $DB->printPosts(PRINT_FORMAT,$postPageNum,$postsperpage,$userID,$getPrivate);

	echo "<div class='pageDiv meta'>";

	// Display page turner
	$selfUrl = $_SERVER['PHP_SELF'];

	// Load original position as well
	if($postID) $originalPageID = $postID;
	if($userID) $originalPageID = $userID;

	if($postPageNum!=1){
	    $turnerPageNum = $postPageNum - 1;
	    echo "<a href='$selfUrl?page=$turnerPageNum&cmtpage=$cmtPageNum&id=$originalPageID#posts' class='actionbutton'>
			<span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span></a>";
			echo "&nbsp; $postPageNum &nbsp;";
	}


	if($isNextPage){
	    $turnerPageNum = $postPageNum + 1;
	    echo "<a href='$selfUrl?page=$turnerPageNum&cmtpage=$cmtPageNum&id=$originalPageID#posts' class='actionbutton'>
			<span class='glyphicon glyphicon-chevron-right' aria-hidden='true'></span></a>";
	}

	echo "</div>";

?>
<br>
</div>

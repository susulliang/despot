<html>
<head>
<body>
<div class="unselectable">
<center>

<?php
	// Get current page number
	if($_GET["cmtpage"]){
		$cmtPageNum = $_GET["cmtpage"];
	} else {
		$cmtPageNum = 1;
	}

	if($_GET["page"]){
		$pageNum = $_GET["page"];
	} else {
		$pageNum = 1;
	}

	// display comments
	$nextPage = $DB->printComments(PRINT_FORMAT,$cmtPageNum,"5",$userID,$postID);

		// Display page turner
		echo "<div class='pageDiv meta'>";
		$selfUrl = $_SERVER['PHP_SELF'];

		// Load original position as well
		if($postID) $originalPageID = $postID;
		else if($userID) $originalPageID = $userID;

		if($cmtPageNum!=1){
			$turnerPageNum = $cmtPageNum - 1;

			echo "<a href='$selfUrl?cmtpage=$turnerPageNum&page=$pageNum&id=$originalPageID#comments' class='actionbutton'>
			<span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span></a>";
			// Page Number
			echo "&nbsp; $cmtPageNum &nbsp;";
		}

		if($nextPage){
			$turnerPageNum = $cmtPageNum + 1;
			echo "<a href='$selfUrl?cmtpage=$turnerPageNum&page=$pageNum&id=$originalPageID#comments' class='actionbutton'>
			<span class='glyphicon glyphicon-chevron-right' aria-hidden='true'></span></a>";
		}

		echo "</div>";

?>
<br>

</center>
</div>
</body>
</head>
</html>

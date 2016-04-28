<html>
<head>
<?php require("ds_header.php");?>
<title>Search: <?php echo $_GET["search"];?></title>
</head>

<body>
<center>
<h2>You are searching for: <?php echo $_GET["search"];?></h2>

<hr>
<?php
	DEFINE(PRINT_FORMAT,"search");
	$_SESSION["redirect"] = NULL;
	if(!$_GET["search"]) showError("Invalid Access","index.php");

	// Get PageNum
	if($_GET["page"]){
	    $pageNum = $_GET["page"];
	} else {
	    $pageNum = 1;
	}

	$searchTarget = $_GET["search"];
	$searchTarget = mysql_real_escape_string($searchTarget);

	$isNextPage = $DB->searchPosts($pageNum, "5", $searchTarget);	


	// Page turner
	echo "<div class='pageDiv'>";

	// Display page turner
	$selfUrl = $_SERVER['PHP_SELF'];

	if($pageNum!=1){
	    $turnerPageNum = $pageNum - 1;

	    echo "<a href='$selfUrl?page=$turnerPageNum&search=$searchTarget' class='actionBtn'> 
	    <img src='img/page-previous.png' class='icon'> </a>";
	}

	    // Page Number
	echo "<font class='textWithButton'>&nbsp; $pageNum &nbsp;</font>";

	if($isNextPage){
	    $turnerPageNum = $pageNum + 1;
	    echo "<a href='$selfUrl?page=$turnerPageNum&search=$searchTarget' class='actionBtn'> 
	    <img src='img/page-next.png' class='icon'>  </a>";
	}

	echo "</div>";
	require("ds_footer.php");
?>
</center>
</body>
</html>
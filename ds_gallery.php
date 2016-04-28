<html>
<head>
<link href="css/style.css" rel="stylesheet" type="text/css">
<body>
<center>
<div class="unselectable">

<?php

	require_once("ds_core.php");

	// Set Gallery Page
	if(!$_GET["gallerypage"]){
		$numPageGallery = 1;
	} else {
		$numPageGallery = $_GET["gallerypage"];
	}


	$nextPage = $DB->printPictures($numPageGallery);

	echo "<div class='pageDiv meta'>";

	// Display page turner
	if($numPageGallery!=1){
	    $turnerPage = $numPageGallery - 1;
	    $selfUrl = $_SERVER['PHP_SELF'];
	    echo "<a href='$selfUrl?gallerypage=$turnerPage#media' class='actionbutton'>
			<span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span></a>";
	    // Page Number
		echo "&nbsp; $numPageGallery &nbsp;";
	}

	if($count>9){
	    $turnerPage = $numPageGallery + 1;
	    $selfUrl = $_SERVER['PHP_SELF'];
	    echo "<a href='$selfUrl?gallerypage=$turnerPage#media' class='actionbutton'>
			<span class='glyphicon glyphicon-chevron-right' aria-hidden='true'></span></a>";
	}

	echo "</div>";

?>
<br>



</div>
</center>
</body>
</head>
</html>

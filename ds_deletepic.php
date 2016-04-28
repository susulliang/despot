<html>
<head>
<title> Deleting... </title>
<body>
<center>
<?php
	include('ds_header.php');

	// Set redirections
	if(!$_SESSION["redirect"]){
		$_SESSION["redirect"] = $_SERVER['HTTP_REFERER'];
		$incomingUrl = $_SERVER['HTTP_REFERER'];
	}
	else{
		$incomingUrl = $_SESSION["redirect"];
	}

	if(!$_GET['id']) // deny direct access
		showError("You cannot access this page directly!", $incomingUrl."#media");

	$picID = $_GET['id'];

	if(!$DB->picExist($picID))
		showError("This picture doesn't exist!", $incomingUrl."#media");

	if($_SESSION["myrole"]!="admin") //User privilege error, redirect
		showError("You don't have the authority to delete this picture!", $incomingUrl);

	if($_GET['confirm']!="yes"){ // Confirmation
		$picUrl = $DB->getPic($picID);
		echo "<img src='$picUrl' class='smallForm'><br>";
		echo "Are you sure you want to delete this Picture? <br>";
		echo "<a href='ds_deletepic.php?id=$picID&confirm=yes' class='btn btn-danger'>Delete</a>&nbsp;&nbsp;";
		echo "<a href='$incomingUrl#media' class='btn btn-default'>Cancel</a><br><br>";
		include('ds_footer.php');
		exit();
	}

	// Get redirections
	$incomingUrl = $_SESSION["redirect"];
	unset($_SESSION["redirect"]);

	// Delete information from database
	$result = $DB->delete("picture",$picID);

	if($result){
		showMsg("Deleted successfully!", $incomingUrl."#media");
	}
	else {
		showError("Deletion failed!", $incomingUrl."#media");
	}





	include('ds_footer.php');
?>
</center>
</body>
</head>
</html>

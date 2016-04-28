<html>
<head>
<title> Deleting... </title>
<body>
<!--HEADER-->
<center>
<?php
	include('ds_header.php');
	
	if(!$_SESSION["redirect"]){
		$_SESSION["redirect"] = $_SERVER['HTTP_REFERER'];
		$incomingUrl = $_SERVER['HTTP_REFERER'];
	}
	else{
		$incomingUrl = $_SESSION["redirect"];
	}

	if(!$_GET['id']) // deny direct access 
		showError("You cannot access this page directly!", $incomingUrl);

	$postID = $_GET['id'];
	
	if(!$DB->postExist($postID)) 
		showError("This post doesn't exist!", $incomingUrl);

	$authorID = $DB->getPostAuthorID;
		
	if($_SESSION["myrole"]!="admin" & $_SESSION["myid"]!=$authorID) //User privilege error, redirect
		showError("You don't have the authority to delete this post!", $incomingUrl);
		

	if($_GET['confirm']!="yes"){ // Confirmation 
		echo "<br>Are you sure you want to delete this post? <br> All information including the 
			comments under this post will be deleted.<br><br>";
		echo "<a href='ds_deletepost.php?id=$postID&confirm=yes' class='btn btn-danger'>Delete</a>&nbsp;&nbsp;";
		echo "<a href='$incomingUrl' class='btn btn-default'>Cancel</a><br><br>";
		include('ds_footer.php');
		exit();
	}

	// Get redirections
	$incomingUrl = $_SESSION["redirect"];
	unset($_SESSION["redirect"]);

	// Delete information from database
	$result=$DB->delete("post",$postID);

	if($result){
		showMsg("Deleted successfully!", $incomingUrl);
	}
	else {
		showError("Sorry, I failed deleting this post!", $incomingUrl);
	}

	include('ds_footer.php');
?>
</center>
</body>
</head>
</html>
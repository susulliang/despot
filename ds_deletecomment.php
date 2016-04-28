<html>
<head>
<title> Deleting... </title>
<body>
<!--HEADER-->
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

	if(!$_GET['id']){ // deny direct access 
		showError("You cannot access this page directly!","index.php");
		exit();
	}

	$cmtID = $_GET['id'];

	if(!$DB->commentExist($cmtID)){ //Wrong Post ID, redirect
		showError("This comment doesn't exist!",$incomingUrl);
		exit();
	}

		
	if($_SESSION["myrole"]!="admin"){ //User privilege error, redirect
		showError("You don't have the authority to delete this comment!",$incomingUrl);
		exit();
	}


	if($_GET['confirm']!="yes"){ // Confirmation 

		echo <<< CONFIRM
		<br>Are you sure you want to delete this comment?<br>
		<a href='ds_deletecomment.php?id=$cmtID&confirm=yes' class='btn btn-danger'>Yes</a>&nbsp;&nbsp;
		<a href='$incomingUrl' class='btn btn-default'>No</a>
CONFIRM;
		include('ds_footer.php');
		exit();
	}

	// Get redirections
	$incomingUrl = $_SESSION["redirect"];
	unset($_SESSION["redirect"]);

	// Delete comment
	$result=$DB->delete("comment",$cmtID);


	if($result){
		showMsg("Deleted successfully!", $incomingUrl);
	}
	else {
		showError("Deletion failed!",$incomingUrl);
	}



	include('ds_footer.php');
?>
</center>
</body>
</head>
</html>

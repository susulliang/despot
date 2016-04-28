<html>
<head>
<title> Deleting User</title>
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
		showError("You cannot access this directly!","index.php");
	}

	$userID = $_GET['id'];
	if(!$DB->userExist($userID)){ //user D.N.E.
		showError("This user doesn't exist!","ds_admin.php#users");
	}

	if($userID==$_SESSION["myid"]){ //user D.N.E.
		showError("You cannot delete yourself!","ds_admin.php#users");
	}
	// CHECK USER AUTHORIZATION

	if($_SESSION["myrole"]!="admin"){ //User privilege error, redirect
		showError("You don't have the authorization to do that!","ds_user.php");
	}

	$targetusername = $DB->getUserName($userID);

	if($_GET['confirm']!="yes"){ // Confirmation 
		echo "<br>Are you sure you want to delete user: $targetusername ?<br>";
		echo "Every post and comment by this user will be deleted.<br>";
		echo "<a href='ds_deleteuser.php?id=$userID&confirm=yes' class='btn btn-danger'>Delete</a>&nbsp;&nbsp;";
		echo "<a href='ds_admin.php#users' class='btn btn-default'>Cancel</a>";
		include('ds_footer.php');
		exit();
	}

	$result=$DB->delete("user",$userID);

	if($result){
		showMsg("User deleted successfully!","ds_admin.php#users");
	}
	else {
		showError("Deleting user failed!","ds_admin.php#users");
	}

	include('ds_footer.php');
?>
</center>
</body>
</head>
</html>

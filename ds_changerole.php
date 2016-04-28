<?php
	require('ds_core.php');

	if(!$_GET['id']||!$_GET['role']){ // deny direct access
		showError("You cannot access this directly!","index.php");
	}
	
	$userID = $_GET['id'];
	$userrole = $_GET['role'];

	if(!$DB->userExist($userID)){ //user D.N.E.
		showError("This user doesn't exist!","ds_admin.php#users");
	}

	if($userID==$_SESSION["myid"]){ //user D.N.E.
		showError("You cannot change your own role!","ds_admin.php#users");
	}

	// CHECK USER AUTHORIZATION
	if($_SESSION["myrole"]!="admin"){ //User privilege error, redirect
		showError("You don't have the authorization to do that!","ds_user.php");
	}

	$result = $DB->updateRole($userID, $userrole);

	if($result){
		showMsg("Role changed successfully!","ds_admin.php#users");
	}
	else {
		showError("Changing user role failed!","ds_admin.php#users");
	}

?>

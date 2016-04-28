<?php
require('ds_core.php');

ob_start();
session_start();

$tbl_name="ds_users";
// Define $myusername and $mypassword
$myusername=$_POST['username'];
$mypassword=$_POST['password'];

// To protect MySQL injection

if(validateStr($myusername)==0 || validatePwd($mypassword)==0) {
	showError("Invalid input!","ds_login.php");
}

//MD5 password
$mypassword=md5($mypassword);

if ($myusername!= "" & $mypassword != ""){
	$sql="SELECT * FROM $tbl_name WHERE name='$myusername' and password='$mypassword'";
	$result=mysql_query($sql);
	$count=mysql_num_rows($result);

	if($count==1){
		$row = mysql_fetch_array($result, MYSQL_ASSOC);

		// Register user information into current session
		$_SESSION['myrole']        = $row["role"];
		$_SESSION['myid']          = $row["id"];
		$_SESSION['myemail']       = $row["email"];
		$_SESSION['mydatecreated'] = $row["datecreated"];
		$_SESSION['loggedin']      = 1;
		$_SESSION['username']      = $myusername;
		showMsg("Logged in succesfully!","ds_user.php");
		exit;
	}
	else {
	showError("Wrong username or password.","ds_login.php");
	}

} else {
	showError("You didn't even put in your username or your password!","ds_login.php");
}
ob_end_flush();
?>

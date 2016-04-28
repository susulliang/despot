<?php
	require('ds_header.php');

	if(!DESPOT_OPEN_REG){
		showError("This site doesn't allow user registration!","index.php");
	}

	$tbl_name="ds_users";

	// Fetch $myusername and $mypassword
	$myusername = $_POST['username'];
	$mypassword = $_POST['password'];
	$myemail    = $_POST['email'];

	// Check username and password integrity
	if ($myusername== "" || $mypassword == "" || $myemail == "")
		showError("You have an incomplete input!","ds_register.php");

	if(!validateStr($myusername))
		showError("Username can only start with letters and
			contains digits and special characters like _!$@#^& with a length 4 ~ 30","ds_register.php");

	if(!validatePwd($mypassword))
		showError("Password can only have a length 5 ~ 30 without illegel characters.","ds_register.php");

	if(!validateEmail($myemail))
		showError("Invalid email address!","ds_register.php");


	// MD5 password and get current Time
	$mypassword = md5($mypassword);
	$time = time();

	// Check if already exist
	if ($DB->usernameOrEmailExist($myusername,$myemail,0)){
		showError("This username or email address already exists!","ds_register.php");
	} else {
		$sql = "INSERT into `ds_users` (name, email, password, role, datecreated)
		VALUES ('$myusername', '$myemail','$mypassword', 'member' ,'$time')";
	  $result=mysql_query($sql);
	}

	if($result)
		showMsg("Registration complete! Login now!","ds_login.php");
	else
		showError("Registration failed!","ds_register.php");

?>

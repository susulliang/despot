<html>
<title> Edit User Profile </title>
<body>
<!--HEADER-->
<center>
<?php
include('ds_header.php');

if(!$_GET['id']){ // default the current user's profile
	$editid = $_SESSION["myid"];
} else {
	$editid = $_GET['id'];
}

//Check Authorization
if($_SESSION["myrole"]!="admin"&$_SESSION["myid"]!=$editid){
	showError("You don't have the authority to edit other user's profile!",
	"ds_user.php");
}


if($_GET["action"]=="edit"){   // Edit Information

	if(!$_POST["username"]||!$_POST["email"]){

		showError("Information incomplete!","ds_edituser.php?id=$editid");
	}

	if(validateStr($_POST["username"])==0) {
	showError("Username can only start with letters and
		contains digits and special characters like _!$@#^& with a length 4 ~ 30","ds_edituser.php?id=$editid");
	}

	if(!validateEmail($_POST["email"])){
		showError("Invalid email address!","ds_edituser.php?id=$editid");
	}

	if($_POST["newpassword"]){ //Process password change

		if($_SESSION["myrole"]=="admin"){

			if(validatePwd($_POST["newpassword"])==0) {
				showError("Your new password can only have a length 5 ~ 30 without illegel characters.","ds_edituser.php?id=$editid");
			}

			$editpwd = md5($_POST["newpassword"]);

		} else {

				if(md5($_POST["oldpassword"])!=getUserPwd($editid)){
					showError("Sorry, the original password you entered is not correct!","ds_edituser.php?id=$editid");
				}

				if(validatePwd($_POST["newpassword"])==0) {
					showError("Your new password can only have a length 5 ~ 30 without illegel characters.","ds_edituser.php?id=$editid");
				}

				$editpwd = md5($_POST["newpassword"]);

		}

	}

	$editusername = $_POST["username"];
	$editemail = $_POST["email"];

	// Check if the username and password exist already
	if ($DB->usernameOrEmailExist($editusername,$editemail,$editid)){
		showError("This username or email address already exists!","ds_edituser.php?id=$editid");
	}

	// Update changes
	$result = $DB->updateUserInfo($editid,$editusername,$editemail,$editpwd);

	if($result){
		if($editid==$_SESSION["myid"]){
			// If I changed my own information, force log out
			session_destroy();
			showMsg("Information successfully updated! Please log in again!","ds_login.php");
		} else {
			showMsg("Information successfully updated!","ds_user.php?id=$editid");
		}
	} else {
		showError("Information update failed!","ds_user.php?id=$editid");
	}





} else {    // or Show Forms

	//Check if this user exists
	$tbl_name="ds_users"; // Table name

	if($DB->userExist($editid)!=1){ //Wrong Post ID, redirect
		showError("Wrong User ID!","ds_user.php");
	}

	$sql="SELECT * FROM $tbl_name WHERE id='$editid'";
	$result=mysql_query($sql);
	$row = mysql_fetch_array($result, MYSQL_ASSOC);

	$editusername = $row["name"];
	$editemail = $row["email"];
	$editpassword = $row["password"];

	echo "<h3>Edit User Profile</h3>";
	echo "<form action='ds_edituser.php?action=edit&id=$editid' method='post' class='smallForm'>";
	echo "My Username<input type='text' name='username' class='form-control' size='35' maxlength='50' placeholder='username' value='$editusername'>";
	echo "My Email<input type='text' name='email' class='form-control' size='35' maxlength='50' placeholder='email' cols='32' rows='10' value='$editemail'>";

	echo "<div class='meta'>If you don't want to change the password, just leave it empty! </div>";
	if($_SESSION["myrole"]!="admin"){ //  Admins don't have to enter old password to set new passwords
		echo "Old Password<br><input type='password' name='oldpassword' class='form-control' size='35' maxlength='50' placeholder='old password' cols='32' rows='10'><br>";

	}

	echo "New Password<input type='password' name='newpassword' class='form-control' size='35' maxlength='50' placeholder='new password' cols='32' rows='10'><br> ";
	echo "<input type='submit' value='Modify' class='btn btn-primary btn-lg'></form>";


}

include('ds_footer.php');
?>

</center>
</body>
</html>

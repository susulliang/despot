<html>
<head>
<title> User </title>
<body>
<center>

<?php
	include('ds_header.php');
	define(PRINT_FORMAT, "personal");
	$_SESSION["redirect"] = NULL;

	if($_GET["id"]){
		$userID = $_GET["id"];
		if(!$DB->userExist($userID)){ // Wrong ID
			showError("This user doesn't exist!","index.php");
		}
	} else if($_SESSION['loggedin']){
		$userID = $_SESSION["myid"];
	} else {
		showError("Please log in to see user profile!","index.php");
	}
?>

		<div class="row personalDiv unselectable">
	        <div class="col-lg-12">
	        	<h3>Profile</h3>
				<?php
				// Display user profile
					
					$DB->printUser($userID);
					// Display info change buttons
					if($_SESSION['loggedin']==1 & 
						($_SESSION['myid']==$userID || $_SESSION['myrole']=="admin")){
						echo <<< EOT
					<a class='btn btn-default btn-xs' href='ds_edituser.php?id=$userID'>
					<span class="glyphicon glyphicon-wrench actionBtn" aria-hidden="true"></span>
					Edit profile</a> &nbsp;

					<a class='btn btn-default btn-xs' href='ds_edituser.php?id=$userID'>
					<span class="glyphicon glyphicon-pencil actionBtn" aria-hidden="true"></span>
					Change password</a>
EOT;
					}
				?>
				<br><br>
	        </div>
	        
	        
	        <div class="col-sm-6">
	        	<h3>Posts</h3>
				<?php
				// Display user posts
					if($_SESSION["myid"]==$userID) $getPrivate = "1";
					include('ds_posts.php');
				?>
	       	</div>

	        <div class="col-sm-6">
	        	<h3>Comments</h3>
				<?php
				// Display user comments
					include('ds_comments.php');				
				?>
	        </div>

        </div>

        	<div class="col-lg-12">
				<?php include("ds_footer.php"); ?>
			</div>

</center>
<script>
    if (location.hash) {
        location.href = location.hash;
    }
</script>

</body>
</head>
</html>

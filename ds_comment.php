<html>
<body>
<center>
<?php
//This page provides comments function to both visitors and users of the site.
//This page displays forms as well as process the forms

session_start();
if(!$postID & !$_GET["postid"]){ //Deny Direct Access
	header("location:index.php");
} else if($_GET["postid"]){
// PROCESS COMMENT
	require('ds_core.php'); //Load necessary core elements

	if($_GET["postid"]){

		$postID = $_GET["postid"];

		if(!$_POST["content"]){
			showError("You should put down some comments!","ds_post.php?id=$postID");
		}

		if($_POST["visitorname"]&$_POST["visitoremail"]){ //VISITOR COMMENT

			if(!DESPOT_OPEN_CMT)
				showError("This site doesn't allow unregistered user to comment!","index.php");

			$tbl_name     = "ds_comments"; // Table name
			$visitorname  = $_POST['visitorname'];
			$content      = $_POST['content'];
			$visitoremail = $_POST['visitoremail'];

			if(!validateStr($visitorname))
				showError("Your name can only start with letters and
				contains digits and special characters like _!$@#^& with a length 4 ~ 30","ds_post.php?id=$postID");

			if(!validateEmail($visitoremail))
				showError("Invalid email address!","ds_post.php?id=$postID");

			$content = stripslashes($content);
			$content = mysql_real_escape_string($content);
			$content = strip_tags($content);

			$time = time();

			$sql = "INSERT into $tbl_name (postid, authorid, visitorname, datemodified, visitoremail,content)
			VALUES ('$postID', 0, '$visitorname', '$time', '$visitoremail', '$content')";
			$result=mysql_query($sql);

			if($result){
				showMsg("Commented successfully!","ds_post.php?id=$postID");
			} else {
				showError("Putting comment failed!","ds_post.php?id=$postID");
			}

		} else { // While the name or email box is empty

			if($_SESSION["loggedin"]==1){ // MEMBER COMMENT

				$tbl_name="ds_comments"; // Table name
				$authorid=$_SESSION['myid'];
				$content=$_POST['content'];

				$content = stripslashes($content);
				$content = mysql_real_escape_string($content);
				$content = strip_tags($content);
				$time = time();

				$sql = "INSERT into $tbl_name (postid, authorid, datemodified,content)
				VALUES ('$postID', '$authorid','$time', '$content')";
				$result=mysql_query($sql);

				if($result)
					showMsg("Commented successfully!","ds_post.php?id=$postID");
				else
					showError("Putting comment failed!","ds_post.php?id=$postID");

			}

			// VISITOR DIDN'T PUT DOWN NAME OR EMAIL
			showError("You gotta write down your name and email!","ds_post.php?id=$postID");
		}

		showMsg("","");
	}
}

if(!$_SESSION["loggedin"] && !DESPOT_OPEN_CMT){ // When open comments are disabled
	echo "Please <a href='ds_login.php'>log in</a> to comment on posts.";
	include("ds_footer.php");
	exit();
}
?>

<h4>Write a comment</h4>

<form action="ds_comment.php?postid=<?php echo $postID; ?>" method="post" class="smallForm">
	<?php
		if(!$_SESSION["loggedin"]){ // VISITOR COMMENTS
		  echo "<div class='meta'>Your email won't not be displayed publicly.</div>";
		  echo "<input type='text' name='visitorname' class='form-control' placeholder='Name'>";
		  echo "<input type='text' name='visitoremail' class='form-control' placeholder='Email'>";
		}
	?>
	<textarea type="text" name="content" class="form-control" placeholder="Comments" rows="3"></textarea>
	<input type="submit" value="Comment" class="btn btn-lg btn-primary btn-block">
</form>
<br>

<center>



</body>
</html>

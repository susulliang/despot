<html>
<head>
<body>
<center>
<!--HEADER-->

<?php
	DEFINE(PRINT_FORMAT, "post");
	UNSET($_SESSION["redirect"]);
	include('ds_header.php');

	if(!$_GET['id']){ 
		// If Id is not specified, get lastest
		$postID = $DB->getLastestPostID();
	} else {
		$postID = $_GET['id'];
	}

	if(!$DB->postExist($postID)){ //check if the post exist
		showError("Oops, this post doesn't exist. ","index.php");
	} else {
		$DB->printPost($postID);
	}
?>
	<div class="unselectable">
		<h4>Comments</h4>
		<?php include('ds_comments.php'); ?>
		<hr>
		<?php include('ds_comment.php'); ?>
	</div>

</center>
<!--FOOTER-->
<?php
include('ds_footer.php');
?>

</body>
</head>
</html>

<?php
require('ds_core.php');

if(!$_GET['id']){ // deny direct access
	header('location:ds_user.php');
	exit();
}

$postID = $_GET['id'];

// Check if this post exists
if(!$DB->postExist($postID)){ //Wrong Post ID, redirect
	header('location:ds_user.php');
	exit();
}

// CHECK USER AUTHORIZATION
$authorid = $DB->getPostAuthorID($postID);
if($_SESSION["myrole"]!="admin" & $_SESSION["myid"]!=$authorid){ //User privilege error, redirect
	header('location:ds_user.php');
	exit();
}

// Fetch the post
$mytitle   = $_POST['title'];
$mycontent = $_POST['content'];
$private   = $_POST['private'];

// Protect from MySQL injection
$mytitle = stripslashes($mytitle);
$mycontent = stripslashes($mycontent);
$mytitle = mysql_real_escape_string($mytitle);
$mycontent = mysql_real_escape_string($mycontent);

// strip prohibited html tags
$mytitle = strip_tags($mytitle);

// Strip excessive spaces and line breaks
$mycontent = preg_replace('/\s*$^\s*/m', "\n", $mycontent);

// Trim the title
$mytitle = trim($mytitle);

if ($mytitle==""){ //Empty title
	$mytitle = "Untitled";
	$private = 1; // save as draft
	echo "Title is empty! Auto named Untitled. This post can only be published as private content.<br>";
}
if ($mycontent==""){
	$private = 1; // save as draft
	echo "Content is empty. This post can only be published as private content.<br>";
}

// Write to database
$result = $DB->post($postID,$private,$_SESSION["myid"],$mytitle,$mycontent);

if($result){
	showMsg("Modified successfully! Changes will take effects very soon.","ds_post.php?id=$postID");
}
else {
	showError("Modification failed!","ds_post.php?id=$postID");
}
?>

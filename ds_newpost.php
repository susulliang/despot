<?php
require("ds_core.php");

if(!$_POST['title']&&!$_POST['content']&&!$_POST['private']){ // block off direct access
	showError("You cannot access this directly!","index.php");
}

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
$forcePrivate = 0;

if ($mytitle==""){
	$mytitle = "Untitled";
	$private = 1; // save as draft
	$forcePrivate = 1;
}
if ($mycontent==""){
	$private = 1; // save as draft
	$forcePrivate = 1;
}

// Write a new post
$result = $DB->post("*",$private,$_SESSION["myid"],$mytitle,$mycontent);

if($result){
	if($forcePrivate){
	showMsg("Title or content is empty, this post is published as private content.","ds_post.php");
	} else {
	showMsg("Published successfully!","ds_post.php");
	}
}
else {
	showError("Publishing post failed!","index.php");
}

?>

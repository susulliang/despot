<?php
/**
 * ---------------------------------------------------------
 * @author Desmond Liang <me@desliang.com>
 * @copyright Copyright (c) 2016, Desmond Liang
 * @version Check ds_core.php
 * @link http://despot.desliang.com
 * @license Open source software: free uses
 * ---------------------------------------------------------
 * This page provides the home page of DeSpot as well as 
 * doing redirections based on the incmoing url.
 * ---------------------------------------------------------
 */
?>
<!DOCTYPE html>

<html>
<head>
<title> Home </title>
</head>
<body>
<center>

<?php

define(PRINT_FORMAT, "homepage");

// Trim leading slash(es)
$path = ltrim($_SERVER['REQUEST_URI'], '/'); 
if(!$path) $path = "index";

// Redirections
switch ($path) {
	case substr($path, 0, 9)=="index.php" || $path=="index":
		// Display home page
		$printFormat = "homepage";
		require('ds_header.php');
		$_SESSION["redirect"] = NULL;
		include('ds_posts.php');
		include('ds_footer.php');
		break;	

	case "login":
		header("location:ds_login.php");
		break;

	case "register":
		header("location:ds_register.php");
		break;

	case "about":
		header("location:ds_about.php");
		break;

	default:
		//404 Redirect
		require_once("ds_core.php");
		showError("Sorry, $path is not found!","index.php");
		break;
}





?>

</center>
</body>

</html>

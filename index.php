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

// Display home page
$printFormat = "homepage";
require('ds_header.php');
$_SESSION["redirect"] = NULL;
include('ds_posts.php');
include('ds_footer.php');
?>

</center>
</body>

</html>

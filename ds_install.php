<html>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/style.css" rel="stylesheet" type="text/css">
<title>
	Installing DeSpot
</title>
<body>
<center>
<br>
<h2>DeSpot Installation</h2>
<br>
Welcome to the installation guide of DeSpot. <br>
Here you will configure you DeSpot to work! Take your time!<br>
<br>
<font color='grey' size=2>
Required environment to run DeSpot: <br>(on a typical Linux distribution)<br>
apache2, php5, php5-mysql, mysql-server<br>
(if you can run wordpress, you can run despot)<br>
</font>
<br>
<form action="ds_install.php?action=install" method="post" class="smallForm">
<h4>Database Configuration</h4>

<input type="text" name="dbhost" class="form-control" placeholder="Database Host" value="localhost">
<input type="text" name="dbuser" class="form-control" placeholder="Database User">
<input type="password" name="dbpwd" class="form-control" placeholder="Database Password">
<input type="text" name="dbname" class="form-control" placeholder="Database Name">

<font color='grey' size=2>DeSpot uses MySql as supporting database.</font><br>

<h4>Administrator Configuration</h4>
<input type="text" name="username" class="form-control" placeholder="Admin username">
<input type="password" name="password" class="form-control" placeholder="Admin password">
<input type="password" name="confpassword" class="form-control" placeholder="Confirm password"><br>
<input type="submit" value="Install" class='btn btn-primary btn-block'>
</form>


<?php

require_once("ds_functions.php");
$config = rwConfig::read('ds_config.php');
if($config['installed']){
	showError("Please manually emtpy the database and delete the 'installed'
		entry in ds_config.php to reinstall DeSpot.","index.php");
	exit();
}


if($_GET["action"]!="install"){
	exit();
}



$myusername = $_POST["username"];
$mypassword = $_POST["password"];
$confpassword = $_POST["confpassword"];;
$dbhost = $_POST["dbhost"];
$dbname = $_POST["dbname"];
$dbuser = $_POST["dbuser"];
$dbpwd = $_POST["dbpwd"];





if(!$myusername || !$mypassword||!$confpassword||!$dbhost
	||!$dbname||!$dbuser||!$dbpwd){
	echo "Please complete the form first to proceed.";
	exit();
}

if($mypassword != $confpassword) {
	echo "Two admin passwords don't match!";
	exit();
}

if(!preg_match("/^[a-zA-Z][0-9a-zA-Z_!$@#^&]{3,30}$/", "$myusername")) {
	echo "Username can only start with letters and
		contains digits and special characters like _!$@#^& with a length 4 ~ 30";
	exit();
}

$mypassword = md5($mypassword);


echo "<br>Connecting to database......<br>";

if(!mysql_connect($dbhost,$dbuser,$dbpwd))
{
     die('Oops connection problem: '.mysql_error());
}
if(!mysql_select_db($dbname))
{
     die('Oops database selection problem: '.mysql_error());
}


echo "<br>Saving database information......<br>";
$config = rwConfig::read('ds_config.php');
$config['dbhost'] = $dbhost;
$config['dbusername'] = $dbuser;
$config['dbpassword'] = $dbpwd;
$config['db_name'] = $dbname;
$config['installed'] = "yes";
rwConfig::write('ds_config.php', $config);



echo "<br>Installing DeSpot......<br>";
//Create table for posts
$sql = "CREATE TABLE $db_name.`ds_posts` ( `id` INT NOT NULL AUTO_INCREMENT ,
		`private` INT(5), `author` INT(10),
		`datemodified` VARCHAR(30) NOT NULL , `title` VARCHAR(50),
		`content` VARCHAR(100000) NOT NULL, PRIMARY KEY(id));";
$result=mysql_query($sql);
if(!$result){
	echo "Something went wrong when creating the table for posts!";
	exit();
} else {
	echo "Table for posts created!";
}
echo "<br>";

//Create table for users
$sql = "CREATE TABLE $db_name.`ds_users` ( `id` INT NOT NULL AUTO_INCREMENT ,
		`name` VARCHAR(50), `email` VARCHAR(50),
		`password` VARCHAR(100) NOT NULL , `role` VARCHAR(20) NOT NULL ,
		`datecreated` VARCHAR(10000) NOT NULL, PRIMARY KEY(id));";
$result=mysql_query($sql);
if(!$result){
	echo "Something went wrong when creating the table for users!";
	exit();
} else {
	echo "Table for users created!";
}
echo "<br>";

//Create table for comments
$sql = "CREATE TABLE $db_name.`ds_comments` ( `id` INT NOT NULL AUTO_INCREMENT ,
		`postid` INT(10), `authorid` INT(10),
		`visitorname` VARCHAR(50) NOT NULL , `datemodified` VARCHAR(30) NOT NULL ,
		`visitoremail` VARCHAR(50) NOT NULL, `content` VARCHAR(2000) NOT NULL ,
		PRIMARY KEY(id));";
$result=mysql_query($sql);
if(!$result){
	echo "Something went wrong when creating the table for comments!";
	exit();
} else {
	echo "Table for comments created!";
}
echo "<br>";

//Create table for media
$sql = "CREATE TABLE $db_name.`ds_media` ( `id` INT NOT NULL AUTO_INCREMENT ,
		`authorid` INT(10),
		`datemodified` VARCHAR(30) NOT NULL ,
		`type` VARCHAR(30) NOT NULL ,
		`url` VARCHAR(50) NOT NULL ,
		PRIMARY KEY(id));";
$result=mysql_query($sql);
if(!$result){
	echo "Something went wrong when creating the table for media!";
	exit();
} else {
	echo "Table for media created!";
}
echo "<br>";


//Create admin user
$time = time();
$sql = "INSERT into `ds_users` (name, email, password, role, datecreated)
VALUES ('$myusername', 'despot@desliang.com' ,'$mypassword', 'admin' ,'$time')";
$result=mysql_query($sql);
if(!$result){
	echo "Something went wrong when creating the admin user!";
	exit();
} else {
	echo "Administrator: $myusername created!";
}
echo "<br>";

//put in a hello world post
$sql = "INSERT into `ds_posts` (private, author, datemodified, title, content) VALUES (0, 1, '$time' ,
	'Hello World','<center>DeSpot is a minimalistic blogging platform!
	This should be your hello world post</center>')";
$result=mysql_query($sql);
if(!$result){
	echo "Something went wrong when creating the first post!";
} else {
	echo "First post created!";
}


echo "<br>Installation Completed!<br>";
echo "<b>To reintall, please manually emtpy the database and delete the 'installed'
		entry in ds_config.php.</b><br>";
echo "<a href='index.php'> Go to my home page</a>";






include("ds_footer.php");
?>
</center>
</body>
</html>

<?php
session_start();
session_destroy();
session_start();
$msg = "You have logged out! <br>";
$_SESSION['msg'] = $msg;
header("location:index.php");
?>

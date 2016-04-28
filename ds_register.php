<html>
<title> Register </title>
<link href="css/signin.css" rel="stylesheet" type="text/css">

<body>
<?php
	include('ds_header.php');
	if(!DESPOT_OPEN_REG)
		showError("This site doesn't allow user registration!","index.php");
?>
<center>
<div class="container unselectable">
	<h2>User Registration</h2>
	<form action="ds_registercheck.php" method="post" class="form-signin">
		<label for="inputUsername" class="sr-only">Username</label>
        <input type="username" name="username" class="form-control" placeholder="Username" required autofocus>

		<label for="inputEmail" class="sr-only">Email</label>
		<input type="email" name="email" class="form-control" placeholder="Email">

		<label for="inputPassword" class="sr-only">Password</label>
		<input type="password" name="password" class="form-control" placeholder="Password">

		<div class="meta">Be careful! You can only put down your password once!</div>

		<input type="submit" value="Register" class='btn btn-primary btn-lg btn-block'>
	</form>
</div>

<center>
<br>
<?php
	include('ds_footer.php');
?>


</body>
</html>

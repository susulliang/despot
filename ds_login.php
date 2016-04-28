<html>
<title> Login </title>
<link href="css/signin.css" rel="stylesheet" type="text/css">

<body>
<!--HEADER-->
<?php
include('ds_header.php');
?>

<center>

    <div class="container unselectable">
		<h2>User Login</h2>
     	<form class="form-signin" action="ds_logincheck.php" method="post">

        <label for="inputUsername" class="sr-only">Username</label>
        <input type="username" name="username" class="form-control" placeholder="Username" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <div class="col-xs-6">
        <a class="btn btn-lg btn-default btn-block" href="ds_register.php">Register</a></div>
        <div class="col-xs-6">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button></div>

      </form>

    </div>


<br>
<center>
<!--FOOTER-->
<?php
include('ds_footer.php');
?>


</body>
</html>

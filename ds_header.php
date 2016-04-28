<DOCTYPE! html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
	<script src="js/jquery.min.js"></script>
</head>

<script>
  setTimeout(function() {
  	$("#msgbar").slideUp("slow");
  $("#errorbar").slideUp("slow");
  }, 2000);
  function showSearch(){
  document.getElementById("searchBox").style.display="block";
  document.getElementById("searchBtn").style.display="none";
  }
</script>

<?php
  // Start the session and load core.
  require_once("ds_core.php");
?>

<center>
  <div id="msgbar" class="alertMsg">
  <?php
  if($_SESSION['msg']){
  echo $_SESSION['msg'];
  unset($_SESSION['msg']);
  }
  ?>
  </div>

  <div id="errorbar" class="alertError">
  <?php
  echo $_SESSION['error'];
  if($_SESSION['error']){
  unset($_SESSION['error']);
  }
  ?>
</center>

  <nav class="navbar navbar-fixed-top unselectable" style="z-index: 10;">
  <div class="container">
  <div class="navbar-header">
  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
  <span class="sr-only">Toggle navigation</span>
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
  </button>
  <a class="navbar-brand siteName" href="index.php"><?php echo DESPOT_SITENAME;?></a>
  <a class="navbar-brand navbar-username visible-md-inline-block visible-lg-inline-block">
    <?php
      if($myusername) echo "Hello, " . $myusername;
    ?>
  </a>
  </div>
  <div id="navbar" class="collapse navbar-collapse" >
  <form class="navbar-form navbar-right" id="searchBox" style="display: none;"
  action="ds_search.php" method="get">
  <input type="text" class="form-control searchBox" placeholder="Search..."
  name="search" autofocus="" >
  </form>

  <ul class="nav navbar-nav navbar-right">

  <?php
  if($myusername ) echo "<li class='visible-xs-inline-block navbar-username'>Hello, " . $myusername . "</li>";
  ?>


  <li><a href='index.php'>
  <span class='glyphicon glyphicon-home' aria-hidden='true'></span>
  <div class='visible-xs-inline-block'>Home</div>
  </a></li>

  <?php
    if($_SESSION["loggedin"]) {

    // Diplsay compose button
    if($_SESSION['myrole']=="admin" || $_SESSION['myrole']=="writer" ){
    echo <<< EOT
    <li><a href='ds_write.php'>
    <span class='glyphicon glyphicon-pencil' aria-hidden='true'></span>
    <div class='visible-xs-inline-block'>Compose</div>
    </a></li>
EOT;
    }

    // Diplsay user button
    echo <<< EOT
    <li><a href='ds_user.php'>
    <span class='glyphicon glyphicon-user' aria-hidden='true'></span>
    <div class='visible-xs-inline-block'>Personal</div>
    </a></li>
EOT;

    // Display admin buttom
    if($_SESSION['myrole']=="admin"){
    echo <<< EOT
    <li><a href='ds_admin.php'>
    <span class='glyphicon glyphicon-cog' aria-hidden='true'></span>
    <div class='visible-xs-inline-block'>Admin</div>
    </a></li>
EOT;

    }

    // Diplsay logout button
    echo <<< EOT
    <li><a href='ds_logout.php'>
    <span class='glyphicon glyphicon-log-out' aria-hidden='true'></span>
    <div class='visible-xs-inline-block'>Log Out</div>
    </a></li>
EOT;

    }
    else

    // Diplsay logout button
    echo <<< EOT
    <li><a href="ds_login.php">
    <span class='glyphicon glyphicon-log-in' aria-hidden='true'></span>
    <div class='visible-xs-inline-block'>Log In</div>
    </a></li>
EOT;
  ?>
  <li id="searchBtn"><a onclick="showSearch();">
  <span class='glyphicon glyphicon-search' aria-hidden='true'></span>
  <div class='visible-xs-inline-block'>Search</div>
  </a></li>
  </ul>


  </div><!--/.nav-collapse -->

  </div>
  </nav>


</div>
<br><br>

</head>
</html>

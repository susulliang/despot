<html>
<head>
<title> Admin </title>
<body>
<!--HEADER-->
<center>

<?php
include('ds_header.php');

forceAdmin();
DEFINE(PRINT_FORMAT,"admin");
UNSET($_SESSION["redirect"]);

?>

<div class="unselectable">
<h3> Admin Panel </h3>

<div class="tabs">
    <div class="btn-group">
        <a class="btn btn-default btn-sm" onclick="switchTab('#general')"> General </a></li>
        <a class="btn btn-default btn-sm" onclick="switchTab('#users')">   Users   </a></li>
        <a class="btn btn-default btn-sm" onclick="switchTab('#posts')">   Posts   </a></li>
        <a class="btn btn-default btn-sm" onclick="switchTab('#media')">   Media   </a></li>
        <a class="btn btn-default btn-sm" onclick="switchTab('#comments')">Comments</a></li>
        <a class="btn btn-default btn-sm" onclick="switchTab('#about')">   About   </a></li>
    </div>


    <div class="tab-content">
        <div id="#general" class='tab'>
            <?php include("ds_admin_general.php"); ?>
        </div>

        <div id="#users" class='tab'>
            <?php include("ds_users.php"); ?>
        </div>

        <div id="#posts" class='tab'>
            <?php include("ds_posts.php"); ?>
        </div>

        <div id="#media" class='tab mediumForm'>
            <?php include("ds_gallery.php"); ?>
            <div class="meta">Click on an image to delete it</div>
        </div>

        <div id="#comments" class='tab'>
            <?php include("ds_comments.php"); ?>
        </div>

        <div id="#about" class='tab'>
            <?php include("ds_about.php"); ?>
        </div>
    </div>
</div>
</div>
<?php


include('ds_footer.php');
?>
</center>

<script>
    function hideAll(){
        document.getElementById("#general").style.display = "none";
        document.getElementById("#users").style.display = "none";
        document.getElementById("#posts").style.display = "none";
        document.getElementById("#media").style.display = "none";
        document.getElementById("#comments").style.display = "none";
        document.getElementById("#about").style.display = "none";
    }

    function switchTab(tabID){
        hideAll();
        document.getElementById(tabID).style.display = "block";
    }

    if (location.hash) {
        hideAll();
        var focusTab = location.hash;
        document.getElementById(focusTab).style.display = "block";
    } else {
        hideAll();
        document.getElementById("#general").style.display = "block";
    }


</script>


</body>
</head>
</html>

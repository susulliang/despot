<div class="unselectable mediumForm">
<center>

<?php
forceAdmin();

$tbl_name="ds_users"; // Table name 
$sql="SELECT * FROM $tbl_name";
$result=mysql_query($sql);
$count=mysql_num_rows($result);
if ($count>0) {
    // Fetch posts
    while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        //output each post
        $crtid = $row["id"];
        $crtusername = $row["name"];
        $crtuseremail = $row["email"];
        $crtuserrole = $row["role"]; //get the name of the author according to the author id

        echo <<< EOT
        <div class="singleUserDiv">
            <span class="label label-success">$crtuserrole</span> 
            <b><a href='ds_user.php?id=$crtid'>$crtusername</a></b> &nbsp;-&nbsp;
            <a href="mailto:$crtuseremail">$crtuseremail</a><br>
            <div class="meta">change role to
            <a class='btn btn-warning btn-xs' href='ds_changerole.php?id=$crtid&role=admin'>Admin</a>
            <a class='btn btn-default btn-xs' href='ds_changerole.php?id=$crtid&role=writer'>Writer</a>
            <a class='btn btn-default btn-xs' href='ds_changerole.php?id=$crtid&role=subscriber'>Subscriber</a> or 
            <a class='btn btn-danger btn-xs' href='ds_deleteuser.php?id=$crtid'>Delete</a>&nbsp;this user <br>
            </div>
        </div>
        <hr>
EOT;
    }

} else {
    echo "Oops, no users here!";
}

?>
<br>
</center>
</div>

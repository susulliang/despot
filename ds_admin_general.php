<div class="unselectable smallForm">
<?php

// This page shows and current configurations for DesPot
// and gives administrators priviliges to reconfigure.


if(PRINT_FORMAT!="admin"){ // Process Change Request

	include("ds_core.php");
	forceAdmin();

	if(!$_POST["sitename"]||!$_POST["siteslogan"]){
		showError("Illegal input!","ds_admin.php");
	}

	if (!preg_match('/^[A-Za-z0-9\s_-]*$/', $_POST["sitename"])) {
    	showError("Site name can only contains letters and numbers with only space, dash and underscore.","ds_admin.php");
	}

	if (!preg_match('/^[A-Za-z0-9\s_-]*$/', $_POST["siteslogan"])) {
    	showError("Site slogan can only contains letters and numbers with only space, dash and underscore.","ds_admin.php");
	}

    if(!ctype_digit($_POST["postsperpage"])||$_POST["postsperpage"]<1||$_POST["postsperpage"]>20) {
        showError("Please only enter a number between 1~20 for number of posts per page.","ds_admin.php");
    }

	$config = rwConfig::read('ds_config.php');
	$config['sitename'] = $_POST["sitename"];
	$config['siteslogan']= $_POST["siteslogan"];
	$config['openregistration'] = $_POST["openregistration"];
	$config['visitorcomment'] = $_POST["visitorcomment"];
  $config['timezoneoffset'] = $_POST["timezoneoffset"];
  $config['posts_per_page'] = $_POST["postsperpage"];
	rwConfig::write('ds_config.php', $config);
	showMsg("Changes applied! Changes will take effects after you refresh the page!",
		"ds_admin.php?refresh=yes");
	exit();
}

if($_GET["refresh"]){
    echo <<< EOT
    Wait 3 seconds and refresh the page!<br>
    <a href='ds_admin.php' class='btn btn-default btn block'>Refresh</a>
EOT;
    include("ds_footer.php");
    exit();
}

// Show forms
if(DESPOT_OPEN_REG==0){
	$openregistrationno = "checked='checked'";
} else {
	$openregistrationyes = "checked='checked'";
}

if(DESPOT_OPEN_CMT==0){
	$visitorcommentno = "checked='checked'";
} else {
	$visitorcommentyes = "checked='checked'";
}

?>

<center>

<form action="ds_admin_general.php" method="post" class="smallForm">

Site name
<input type="text" name="sitename" class="form-control" placeholder="Sitename"
<?php echo "value='$sitename'"?>>

Site slogan
<input type="text" name="siteslogan" class="form-control" placeholder="Slogan"
<?php echo "value='$siteslogan'"?>>

Timezone
<select name="timezoneoffset" class='form-control' id='timezonedropdown'>
        <option value="-12" >(GMT -12:00) Eniwetok, Kwajalein</option>
        <option value="-11" >(GMT -11:00) Midway Island, Samoa</option>
        <option value="-10" >(GMT -10:00) Hawaii</option>
        <option value="-9"  >(GMT -9:00) Alaska</option>
        <option value="-8"  >(GMT -8:00) Pacific Time (US &amp; Canada)</option>
        <option value="-7"  >(GMT -7:00) Mountain Time (US &amp; Canada)</option>
        <option value="-6"  >(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
        <option value="-5"  >(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
        <option value="-4"  >(GMT -4:00) Atlantic Time (Canada), La Paz, Santiago</option>
        <option value="-3"  >(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
        <option value="-2"  >(GMT -2:00) Mid-Atlantic</option>
        <option value="-1"  >(GMT -1:00) Azores, Cape Verde Islands</option>
        <option value="0"   >(GMT) Western Europe Time, London, Lisbon, Casablanca, Greenwich</option>
        <option value="1"   >(GMT +1:00) Brussels, Copenhagen, Madrid, Paris</option>
        <option value="2"   >(GMT +2:00) Kaliningrad, South Africa, Cairo</option>
        <option value="3"   >(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
        <option value="4"   >(GMT +4:00) Abu Dhabi, Muscat, Yerevan, Baku, Tbilisi</option>
        <option value="5"   >(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
        <option value="6"   >(GMT +6:00) Almaty, Dhaka, Colombo</option>
        <option value="7"   >(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
        <option value="8"   >(GMT +8:00) Beijing, Chongqing, Singapore, Hong Kong</option>
        <option value="9"   >(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
        <option value="10"  >(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
        <option value="11"  >(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
        <option value="12"  >(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
</select>

Posts per page
<input type="text" name="postsperpage" class="form-control" placeholder="Number between 1 ~ 20" <?php echo "value='$postsperpage'"?>>


Open registration <br>
<input type='radio' id='radio1' name='openregistration' value=1 <?php echo "$openregistrationyes"; ?>>&nbsp;
<label for="radio1">Yes</label>
<input type='radio' id='radio2' name='openregistration' value=0 <?php echo "$openregistrationno"; ?>>
<label for="radio2">Nope</label><br>

Visitor comments <br>
<input type='radio' id='radio3' name='visitorcomment' value=1 <?php echo "$visitorcommentyes"; ?>> &nbsp;
<label for="radio3">Allowed</label>
<input type='radio' id='radio4' name='visitorcomment' value=0 <?php echo "$visitorcommentno"; ?>>
<label for="radio4">Nope</label><br>

<script>
document.getElementById('timezonedropdown').selectedIndex=<?php echo $timezoneoffset + 12; ?>;
</script>

<input type="submit" value="Apply" class='btn btn-primary'>
</form>
<br>

</center>

</div>

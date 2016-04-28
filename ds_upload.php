<script>
	parent.showUploadStatus();
</script>

<link href="css/style.css" rel="stylesheet" type="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<font color="grey" size=2><center>

<?php
require_once("ds_core.php");
//if they DID upload a file...
if($_FILES['uploadFile']['name']){
	//if no errors...
	if(!$_FILES['uploadFile']['error']){
		//modify the future file name and validate the file
		$new_file_name = generateRandomString(16); //rename file

		//Get file extension
		$ext = pathinfo($_FILES['uploadFile']['name'], PATHINFO_EXTENSION);

		//Limit file size to 2MB
		if($_FILES['uploadFile']['size'] > (2048000)) 
		{
			echo 'Oops!  Your file\'s size is to large. Size limit is 2MB.';
		} else {

		//Move the file to the correct location
		$fileUrl = './media/' . $new_file_name . "." . $ext;

		move_uploaded_file($_FILES['uploadFile']['tmp_name'], $fileUrl);
		

		//Index this file into the database
			session_start();
			$tbl_name="ds_media"; // Table name 
			$time = time();
			$sql = "INSERT into $tbl_name (authorid, datemodified, type, url) 
			VALUES ('$myid', '$time', 'image', '$fileUrl')";
			$result=mysql_query($sql);

		if(!$result){
			echo "Something went wrong when indexing this file!<br>";
		} 

		//Get the filename
		echo "<input type='hidden' value='" . $new_file_name . "." . $ext . "' id='filename'>";

		echo '✅ Upload completed. <br> Image was inserted into the text editor.<br>';
		}
	}
	//if there is an error...
	else
	{
		//set that to be the returned message
		echo 'Ooops!  Your upload triggered the following error:  '.$_FILES['uploadFile']['error'];
	}
} else {
	echo "No file selected.";
}
?>

</center></font>

<script>
	var filename = document.getElementById('filename').value;
	filename = "media/" + filename;
	parent.image(filename);
	parent.refreshGallery();
	parent.hideAll();
</script>
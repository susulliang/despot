<html>
<title> Editor </title>
<body>
<!--HEADER-->
<center>
<?php
include('ds_header.php');
include('ds_userbar.php');

forceWriter();
?>


<script type="text/javascript">

		function showhide(id) {
			//Hide all boxes first
			hideAll();

			//Display collapse button
			document.getElementById("collapse").style.display="inline-block";

	    	//Show/hide selected box
		    var e = document.getElementById(id);
		    e.style.display = (e.style.display == 'block') ? 'none' : 'block';
		}

		function hideAll(){
			document.getElementById("linkBox").style.display="none";
	    	document.getElementById("uploadBox").style.display="none";
	    	document.getElementById("collapse").style.display="none";
		}

		function upload_start(){
  			document.getElementById("hidden_upload").style.display="none";
  			document.getElementById("uploading").style.display="block";
 		}

 		function showUploadStatus(){
  			document.getElementById("hidden_upload").style.display="block";
  			document.getElementById("uploading").style.display="none";
 		}

 		window.addEventListener("load", function(){
	        //first check if execCommand and contentEditable attribute is supported or not.
	        if(document.contentEditable != undefined && document.execCommand != undefined){
	           alert("HTML5 Document Editing API Is Not Supported");
	        }
	        else
	        {
	            document.execCommand('styleWithCSS', false, true);
	        }
    	}, false);
	    //makes the selected text as hyperlink.
	    function link()
	    {
	        var url = document.getElementById('linkUrl').value;
	        document.execCommand("createLink", false, url);
	    }

	    function addWebImage()
	    {
	        var url = document.getElementById('imageUrl').value;
	        if(url!=""){
	        	image(url);
	    	}
	    }

	    function image(url){
	    	var textbox = document.getElementById("editor");
    		textbox.scrollIntoView();
    		textbox.focus();
	    	document.execCommand("insertImage", false, url);
	    }

	    function editText(action){
	    	document.execCommand(action, false, null);
	    }
	    
	    function prepareHtml(){
	    	//put the content of editor into html code for submission
	    	document.getElementById("myTexts").value = document.getElementById("editor").innerHTML;
	    }

	    function refreshGallery(){
	    	document.getElementById("galleryBox").src="ds_gallery.php";
	    }

	    function handlepaste (elem, e) {
		    var savedcontent = elem.innerHTML;
		    if (e && e.clipboardData && e.clipboardData.getData) {// Webkit - get data from clipboard, put into editdiv, cleanup, then cancel event
		        if (/text\/html/.test(e.clipboardData.types)) {
		            elem.innerHTML = e.clipboardData.getData('text/plain');
		        }
		        else if (/text\/plain/.test(e.clipboardData.types)) {
		            elem.innerHTML = e.clipboardData.getData('text/plain');
		        }
		        else {
		            elem.innerHTML = "";
		        }
		        waitforpastedata(elem, savedcontent);
		        if (e.preventDefault) {
		                e.stopPropagation();
		                e.preventDefault();
		        }
		        return false;
		    }
		    else {// Everything else - empty editdiv and allow browser to paste content into it, then cleanup
		        elem.innerHTML = "";
		        waitforpastedata(elem, savedcontent);
		        return true;
		    }
		}

		function waitforpastedata (elem, savedcontent) {
		    if (elem.childNodes && elem.childNodes.length > 0) {
		        processpaste(elem, savedcontent);
		    }
		    else {
		        that = {
		            e: elem,
		            s: savedcontent
		        }
		        that.callself = function () {
		            waitforpastedata(that.e, that.s)
		        }
		        setTimeout(that.callself,20);
		    }
		}

		function processpaste (elem, savedcontent) {
		    pasteddata = elem.innerHTML;
		    //^^Alternatively loop through dom (elem.childNodes or elem.getElementsByTagName) here

		    elem.innerHTML = savedcontent;

		    // Do whatever with gathered data;
		    document.execCommand("insertText", false, pasteddata);
		}


</script>

<?php


// Editor Tool Bar
$toolbar = <<< EOT
	<div class="btn-group">
	<button class="btn btn-default" onclick="editText('bold')">
	<span class="glyphicon glyphicon-bold actionBtn" aria-hidden="true"></span></button>
	<button class="btn btn-default" onclick="editText('italic')">
	<span class="glyphicon glyphicon-italic actionBtn" aria-hidden="true"></span></button>
	<button class="btn btn-default" onclick="editText('underline')">
	<span class="glyphicon glyphicon-text-color actionBtn" aria-hidden="true"></span></button>
	<button class="btn btn-default" onclick="editText('justifyLeft')">
	<span class="glyphicon glyphicon-align-left actionBtn" aria-hidden="true"></span></button>
	<button class="btn btn-default" onclick="editText('justifyCenter')">
	<span class="glyphicon glyphicon-align-center actionBtn" aria-hidden="true"></span></button>
	<button class="btn btn-default" onclick="editText('justifyRight')">
	<span class="glyphicon glyphicon-align-right actionBtn" aria-hidden="true"></span></button>
    <button class="btn btn-default" onclick="showhide('linkBox')">
    <span class="glyphicon glyphicon-link actionBtn" aria-hidden="true"></span></button>	
    <button class="btn btn-default" onclick="showhide('uploadBox')">
    <span class="glyphicon glyphicon-picture actionBtn" aria-hidden="true"></span></button>
    </div>
    <button class="btn btn-default" onclick="hideAll()" style="display:none" id="collapse"><span class="glyphicon glyphicon-menu-up actionBtn" aria-hidden="true"></span></button>

	<div id="uploadBox" style="display:none" class="smallForm">
		Upload a file<br>

		<form name="uploadForm" method="post" action="ds_upload.php" target="hidden_upload" enctype="multipart/form-data">
		<input type="file" name="uploadFile" accept="image/*" >
		<input type="submit" value="Upload" class="btn btn-default" onclick="upload_start()">
		</form>

		<div class="meta" id="uploading" style="display:none">Uploading...</div>
		<iframe id="hidden_upload" name="hidden_upload" style="display:none"></iframe>

		or Insert an image on the web<br>
		<input type="text" id="imageUrl" name="url" class="form-control" placeholder="Url of the web image">
		<input type="button" value="Insert" class="btn btn-default" onclick="addWebImage()">

		<br><br>or Choose one from the gallery<br>
		<iframe id="galleryBox" name="galleryBox" src="ds_gallery.php"
		class="mediumForm"></iframe>
	</div>


	<div id="linkBox" style="display:none" class="smallForm">
		Apply a hyperlink to selected text<br>
		<input type="text" id="linkUrl" name="url" class="form-control" placeholder="http://" 
			value="http://">
		<input type="button" value="Apply" class='btn btn-default' onclick="link()">
	</div>

EOT;




if(!$_GET['id']){
	// NEW POST
	echo <<< EOT
	<h3>Write a new post</h3>
	$toolbar
	<form action="ds_newpost.php" method="post" onsubmit="prepareHtml()" class="editor">
		<input type='text' name='title' class='form-control' placeholder='Title' size='35' maxlength='50'>
		<textarea type='text' name='content' maxlength='10000' id='myTexts' 
			style="display:none"></textarea>
		<div class="contentBox">
			<div class="form-control contentBox" contenteditable="true" spellcheck="true" 
			tabindex="-1" align="left" id="editor" onpaste='handlepaste(this, event)'>
			</div>
		</div>
		<input type='radio' id='radio1' name='private' value=0  checked='checked'> &nbsp;
		<label for="radio1">Public</label> &nbsp;
		<input type='radio' id='radio2' name='private' value=1> 
		<label for="radio2">Private</label><br>
	    <input type='submit' value='Publish' class='btn btn-primary'>
    </form><br>
EOT;

} else {
	// EDIT EXISTING POST
	$postID = $_GET['id'];

	//Check if this post exists
	
	if(!$DB->postExist($postID)){ //Wrong Post ID, redirect
		showError("Wrong post!","ds_user.php");
		exit();
	}

	list($private,$authorid,$datemodified,$mytitle,$mycontent) = $DB->getPost($postID); 

	if($_SESSION["myrole"]!="admin" & $_SESSION["myid"]!=$authorid){ //User privilege error, redirect
		showError("You don't have the authorization to edit this post.","ds_user.php");
		exit();
	}

	echo <<< EOT
	<h3>Edit a post</h3>
	$toolbar
	<form action='ds_modifypost.php?id=$postID' method='post' onsubmit="prepareHtml()" class="editor">
	<input type='text' name='title' class='form-control' size='35' maxlength='50' placeholder='Title' value='$mytitle'>
	<textarea type='text' name='content' maxlength='10000' id='myTexts' 
			style="display:none"> </textarea>
	<div class="contentBox">
		<div class="form-control contentBox" contenteditable="true" spellcheck="true" tabindex="-1"
		id="editor" onpaste='handlepaste(this, event)'>
			$mycontent
		</div>
	</div>

EOT;

	if($private==0){
		echo <<< EOT
	<input type='radio' id='radio1' name='private' value=0  checked='checked'> &nbsp;
	<label for="radio1">Public</label> &nbsp;
	<input type='radio' id='radio2' name='private' value=1> 
	<label for="radio2">Private</label><br>
EOT;

	} else {
		echo <<< EOT
	<input type='radio' id='radio1' name='private' value=0> &nbsp;
	<label for="radio1">Public</label> &nbsp;
	<input type='radio' id='radio2' name='private' value=1  checked='checked'> 
	<label for="radio2">Private</label><br>
EOT;

	}
	
	echo "<input type='submit' value='Modify' class='btn btn-primary' 
			onclick='translateEditorContent()'></form><br>";
}

include('ds_footer.php');
?>

</center>
</body>
</html>

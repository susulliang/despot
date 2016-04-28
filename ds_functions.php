<?php
/**
 * -----------------------------------------------------
 * @author Desmond Liang <me@desliang.com>
 * @copyright Copyright (c) 2016, Desmond Liang
 * @version see ds_core.php
 * @link http://despot.desliang.com
 * @license GNU General Public License (see LICENSE.txt)
 * -----------------------------------------------------
 * This file contains vital functions for DeSpot
 * -----------------------------------------------------
 */



/**
* ------------------------------------------------------
* this rwConfig class is used to r/w config files
* ------------------------------------------------------
*/
class rwConfig{
	public static function read($filename)
	{
		$config = include $filename;
		return $config;
	}
	public static function write($filename, array $config)
	{
		$config = var_export($config, true);
		file_put_contents($filename, "<?php return $config ;?>");
	}
}



/**
* ------------------------------------------------------
* this DB class groups all database actions together
* ------------------------------------------------------
*/
class DB{
	// Database Class for database read and write
	var $db_hostname;
	var $db_username;
	var $ds_passwd;
	var $db_dbname;

	/**
	* --------------------------------------------------
	* database initialization function
	* @param configuration array
	* --------------------------------------------------
	*/
	public function init($configs){
		$this->ds_hostname = $configs["dbhost"];
		$this->db_username = $configs["dbusername"];
		$this->ds_passwd = $configs["dbpassword"];
		$this->db_dbname = $configs["db_name"];
		$this->connect();
	}


	/**
	* --------------------------------------------------
	* database connect
	* --------------------------------------------------
	*/
	private function connect(){
		if(!mysql_connect($this->db_hostname,$this->db_username,$this->ds_passwd))
			die('oops connection problem ->'.mysql_error());
		if(!mysql_select_db($this->db_dbname))
			die('oops database selection problem ->'.mysql_error());
	}


	/**
	* --------------------------------------------------
	* database query
	* @param mysql query
	* @return array of the first row
	* --------------------------------------------------
	*/
	public function getRow($query){
		$result=mysql_query($query);
		return mysql_fetch_array($result, MYSQL_ASSOC);
	}


	/**
	* --------------------------------------------------
	* get the single post function
	* @param post ID
  * @return array of information of that post
  * --------------------------------------------------
	*/
	public function getPost($postID){
		$tbl_name="ds_posts";
		$row = $this->getRow("SELECT * FROM $tbl_name WHERE id=$postID");
		return array($row["private"],$row["author"],$row["datemodified"],$row["title"],$row["content"]);
	}


	/**
	* --------------------------------------------------
	* get a single picture
	* @param picture ID
   	* @return picture URL
   	* --------------------------------------------------
	*/
	public function getPic($picID){
		$tbl_name="ds_media";
		$row = $this->getRow("SELECT * FROM $tbl_name WHERE id=$picID");
		return $row["url"];
	}


	/**
	* --------------------------------------------------
	* get the authorID from a post
	* @param post ID
   	* @return Author ID
   	* --------------------------------------------------
	*/
	public function getPostAuthorID($postID){
		$tbl_name="ds_posts";
		$row = $this->getRow("SELECT * FROM $tbl_name WHERE id=$postID");
		return $row["author"];
	}

	/**
	* --------------------------------------------------
	* get the lastest post's ID
	* @return ID of the lastest post
	* --------------------------------------------------
	*/
	public function getLastestPostID(){
		$tbl_name="ds_posts";
		$row = $this->getRow("SELECT * FROM $tbl_name ORDER BY id DESC LIMIT 0, 1;");
		return $row["id"];
	}


	/**
	* --------------------------------------------------
	* check if a post exists
	* @param postID
   	* @return True or False
   	* --------------------------------------------------
	*/
	public function postExist($postID){
		$tbl_name="ds_posts"; // Table name
		$sql="SELECT * FROM $tbl_name WHERE id=$postID";
		$result=mysql_query($sql);
		$count=mysql_num_rows($result);
		return ($count==1);
	}

	/**
	* --------------------------------------------------
	* check if a picture exists
	* @param postID
   	* @return True or False
   	* --------------------------------------------------
	*/
	public function picExist($picID){
		$tbl_name="ds_media"; // Table name
		$sql="SELECT * FROM $tbl_name WHERE id=$picID";
		$result=mysql_query($sql);
		$count=mysql_num_rows($result);
		return ($count==1);
	}


	/**
	* --------------------------------------------------
	* update user's role
	* @param UserID
	* @param target Role
	* --------------------------------------------------
	*/
	public function updateRole($userID,$role){
		$tbl_name = "ds_users";
		$sql = "UPDATE $tbl_name SET role='$role' WHERE id='$userID'";
		$result=mysql_query($sql);
		return $result;
	}

	/**
	* --------------------------------------------------
	* create or update a post
	* @param postID: "*" means new post
	* @param private session_status
	* @param post title
	* @param post content
	* @param author id
	* @return result
	* --------------------------------------------------
	*/
	public function post($postID, $private, $authorID, $postTitle, $postContent){
		$time = time();
		$tbl_name = "ds_posts";
		if($postID=="*"){
			// New Post
			$sql = "INSERT into $tbl_name (private, author, datemodified, title, content)
			VALUES ('$private', '$authorID', '$time', '$postTitle', '$postContent')";
		} else {
			// Update existing post
			$sql = "UPDATE $tbl_name SET title='$postTitle', content='$postContent',
			datemodified='$time', private=$private WHERE id='$postID'";
		}
		$result = mysql_query($sql);
		return $result;
	}

	/**
	* --------------------------------------------------
	* delete an entry from the database
	* @param entry type
	* @param entry ID
   	* @return operation result
   	* --------------------------------------------------
	*/
	public function delete($type, $ID){
		// Delete information from database
		if($type=="post") {
			// Delete the post
			$tbl_name="ds_posts";
			$sql="DELETE FROM $tbl_name WHERE id=$ID";
			$result=mysql_query($sql);

			// Delete comments under the post
			$tbl_name="ds_comments";
			$sql="DELETE FROM $tbl_name WHERE postid=$ID";
			$result=mysql_query($sql);

		} else if($type=="comment") {
			// Delete one comment
			$tbl_name="ds_comments"; // Table name
			$sql="DELETE FROM $tbl_name WHERE id=$ID";
			$result=mysql_query($sql);

		} else if($type=="user") {
			// Delete one user
			$tbl_name="ds_users"; // Table name
			$sql="DELETE FROM $tbl_name WHERE id=$ID";
			$result=mysql_query($sql);

			$tbl_name="ds_posts";
			$sql="DELETE FROM $tbl_name WHERE author=$ID";
			$result=mysql_query($sql);

			$tbl_name="ds_comments";
			$sql="DELETE FROM $tbl_name WHERE authorid=$ID";
			$result=mysql_query($sql);

		} else if($type=="picture") {
			// Delete local file
			$FILEPATH=$this->getPic($ID);
			unlink($FILEPATH);

			// Unlink from database
			$tbl_name="ds_media"; // Table name
			$sql="DELETE FROM $tbl_name WHERE id=$ID";
			$result=mysql_query($sql);
		}
			return $result;
	}

	/**
	* --------------------------------------------------
	* return the username of a specific userID
	* @param postID
  * @return username
  * --------------------------------------------------
	*/
	public function getUserName($userID){
		$tbl_name="ds_users"; // Table name
		$row=$this->getRow("SELECT * FROM $tbl_name WHERE id=$userID");
		return $row["name"];
	}

	/**
	* --------------------------------------------------
	* return the Role of a specific userID
	* @param postID
  * @return Role
  * --------------------------------------------------
	*/
	public function getRole($userID){
		$tbl_name="ds_users"; // Table name
		$row=$this->getRow("SELECT * FROM $tbl_name WHERE id=$userID");
		return $row["role"];
	}


	/**
	* --------------------------------------------------
	* return all the information of a specific userID
	* @param postID
  * @return array of information
  * --------------------------------------------------
	*/
	public function getUser($userID){
		$tbl_name="ds_users"; // Table name
		$row=$this->getRow("SELECT * FROM $tbl_name WHERE id=$userID");
		return array($row["name"],$row["role"],$row["datecreated"],$row["email"]);
	}


	/**
	* --------------------------------------------------
	* print out all the information of a specific userID
	* @param postID
  * --------------------------------------------------
	*/
	public function printUser($userID){
		$tbl_name="ds_users"; // Table name
		$row=$this->getRow("SELECT * FROM $tbl_name WHERE id=$userID");
		$userName = $row["name"];
		$userRole = $row["role"];
		$userDate = $row["datecreated"];
		$userEmail= $row["email"];
		$userDate = date("m-d-Y",$userDate);

		echo <<< USER_INFO
		Username: $userName <br>
		Role: $userRole <br>
		Email: $userEmail <br>
		Date Registered: $userDate <br>
USER_INFO;
	}


	/**
	* --------------------------------------------------
	* return password of a specific userID
	* @param postID
   	* @return MD5 version of that password
   	* --------------------------------------------------
	*/
	public function getUserPwd($userID){
		$tbl_name="ds_users"; // Table name
		$row=$this->getRow("SELECT * FROM $tbl_name WHERE id=$userID");
		return $row["password"];
	}


	/**
	* --------------------------------------------------
	* check if a post exists
	* @param postID
  * @return True or False
  * --------------------------------------------------
	*/
	public function userExist($userID){
		$tbl_name="ds_users"; // Table name
		$sql="SELECT * FROM $tbl_name WHERE id=$userID";
		$result=mysql_query($sql);
		$count=mysql_num_rows($result);
		return ($count==1);
	}


	/**
	* --------------------------------------------------
	* check if a comment exists
	* @param commentID
  * @return True or False
  * --------------------------------------------------
	*/
	public function commentExist($cmtID){
		$tbl_name="ds_comments"; // Table name
		$sql="SELECT * FROM $tbl_name WHERE id=$cmtID";
		$result=mysql_query($sql);
		$count=mysql_num_rows($result);
		return ($count==1);
	}


	/**
	* --------------------------------------------------
	* print a single post according to the given post ID
	* @param postID
  * --------------------------------------------------
	*/
	public function printPost($postID){
		$tbl_name="ds_posts";
		$row = $this->getRow("SELECT * FROM $tbl_name WHERE id=$postID");

		// Set parameters
		$postPrivate=$row["private"];
		$postTitle=$row["title"];
		$postContent=$row["content"];
		$postDate=$row["datemodified"];
		$postDate=date("m-d-Y  h:i:s  a",$postDate);
		$postAuthorID=$row["author"];
		$postAuthorName = $this->getUserName($postAuthorID);

		//Set private Label
		if($row["private"])
			$privateTag = "<span class='label label-default'>Private</span>";
		else
			$privateTag = "";

		if($postPrivate==1 & (!$_SESSION['myid'] || $_SESSION['myid']!= $postAuthorID)){
			showError("Oops, you don't seem to have authorization to view this post!","index.php");
		} else {
			// Determine user privilege
			if($_SESSION['myrole']=="admin" || $_SESSION['myid']==$postAuthorID){
				$actionButtons = <<< ACTION_BUTTONS
					<a href='ds_write.php?id=$postID' class='actionBtn'>
					<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
					</a>
					<a href='ds_deletepost.php?id=$postID' class='actionBtn'>
					<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
					</a>
ACTION_BUTTONS;
			} else {
				$actionButtons = "";
			}
			echo <<< DISPLAY_POST
			<title> $postTitle </title>
			<div class="container unselectable">
				<h1>$privateTag $postTitle</h1>
				<div class="meta">
					Author:
					<a href="ds_user.php?id=$postAuthorID">
					$postAuthorName</a> &nbsp;
					Date Modified: $postDate &nbsp;
					$actionButtons
				</div>
				$postContent <br><hr>
			</div>
DISPLAY_POST;
		}

	}


	/**
	* --------------------------------------------------
	* Check if the username and email already exist on other users
	* @param username
	* @param email
	* @param user's own ID
	* --------------------------------------------------
	*/
	public function usernameOrEmailExist($username,$email,$ownID){
		$tbl_name = "ds_users";
		$sql="SELECT * FROM $tbl_name WHERE (name='$username' OR email='$email') AND id!='$ownID'";
		$result=mysql_query($sql);
		$count=mysql_num_rows($result);
		return ($count>=1);
	}

	/**
	* --------------------------------------------------
	* Update user information
	* @param username
	* @param email
	* @param user's own ID
	* --------------------------------------------------
	*/
	public function updateUserInfo($userID,$username,$email,$password){
		$tbl_name = "ds_users";
		if($password){ // Update information as well as password
			$sql = "UPDATE $tbl_name SET name='$username', email='$email', password='$password' WHERE id='$userID'";
			$result=mysql_query($sql);
		} else { // Update only username and email
			$sql = "UPDATE $tbl_name SET name='$username', email='$email' WHERE id='$userID'";
			$result=mysql_query($sql);
		}
		return $result;
	}


	/**
	* --------------------------------------------------
	* print multiple posts function
	* @param Print format, default = Homepage format
	* @param Page number of the post, default = 1
	* @param How many posts to be shown per page, default = 10
	* @param Filter post by an author's name, default = * (no filtering)
	* @param Filter post by privacy condition, default = 0 (Will NOT get private posts)
	* --------------------------------------------------
	*/
	public function printPosts($format="homepage",$pageNum="1",$postsPerPage="10",$authorID="*",$getPrivate="0"){

		if($format!="homepage")$postsPerPage = 5;

		// Initialize the range for showing posts
		$limitStart = ($pageNum - 1) * $postsPerPage;
		$limitEnd = $limitStart + $postsPerPage + 1;

		// Select posts from database
		$tbl_name="ds_posts";

		if($authorID=="*"){
			// Show posts that are not private
			$sql="SELECT * FROM $tbl_name WHERE private=0 ORDER BY id DESC LIMIT $limitStart , $limitEnd;";
		} else if($getPrivate=="1"){
			// Show all posts from selected user
			$sql="SELECT * FROM $tbl_name WHERE author='$authorID' ORDER BY id DESC LIMIT $limitStart , $limitEnd;";
		} else {
			// Show public posts from selected user
			$sql="SELECT * FROM $tbl_name WHERE author='$authorID' AND private='0' ORDER BY id DESC LIMIT $limitStart , $limitEnd;";
		}

		// Query Mysql
		$result=mysql_query($sql);
		$count=mysql_num_rows($result);


		if ($count>0) {
			// Fetch posts
			for($i=0; $i<$count & $i<$postsPerPage; $i++) {

				// Fetch the array
				$row = mysql_fetch_array($result, MYSQL_ASSOC);

				// Set some parameters
				$dateModified = date("m-d-Y",$row["datemodified"]);

				$crtAuthorID = $row["author"];
				$crtPostID = $row["id"];
				$crtPostTitle = $row["title"];
				$crtPostContent = $row["content"];
				$crtAuthorName = $this->getUserName($crtAuthorID);

				//Set private Label
				if($row["private"])
					$privateTag = "<span class='label label-default'>Private</span>";
				else
					$privateTag = "";

				// Determine user privilege
				if($_SESSION['myrole']=="admin" || $_SESSION['myid']==$crtAuthorID){
					$actionButtons = <<< ACTION_BUTTONS
						<a href='ds_write.php?id=$crtPostID' class='actionBtn'>
						<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
						</a>
						<a href='ds_deletepost.php?id=$crtPostID' class='actionBtn'>
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
						</a>
ACTION_BUTTONS;
				} else {
					$actionButtons = "";
				}


				if($format=="homepage"){
					echo <<< HOMEPAGE_POSTS
					<div class="container">
					<a href='ds_post.php?id=$crtPostID' class='postTitle'><h1>$crtPostTitle</h1></a>
					<div class="meta">
					Author:&nbsp;
					<a href='ds_user.php?id=$crtAuthorID'>$crtAuthorName</a>&nbsp;
					Date: $dateModified &nbsp;
					$actionButtons
					</div>
					$crtPostContent
					<hr>
					</div>
HOMEPAGE_POSTS;
				}


				if($format=="personal"){
					echo <<< PERSONAL_POSTS
					$privateTag <a href='ds_post.php?id=$crtPostID'>$crtPostTitle</a>
					<div class="meta">
					($dateModified)
					$actionButtons
					</div>
PERSONAL_POSTS;
				}

				if($format=="admin"){
					echo <<< ADMIN_POSTS
					<a href='ds_post.php?id=$crtPostID'>$crtPostTitle</a>
					<div class="meta">
					by $crtAuthorName
					($dateModified)
					$actionButtons
					</div>
ADMIN_POSTS;
				}

			}
		} else {
			echo "<div class='meta'>Oops, there's no post here on this page at all!</div>";
		}

		// Return if there's a next page
		return ($count>$postsPerPage);
	}


	/**
	* --------------------------------------------------
	* search&print posts function
	* @param Page number of the post, default = 1
	* @param How many posts to be shown per page, default = 10
	* @param match string for search
	* --------------------------------------------------
	*/
	public function searchPosts($pageNum="1",$postsPerPage="10",$search="*"){

		// Initialize the range for showing posts
		$limitStart = ($pageNum - 1) * $postsPerPage;
		$limitEnd = $limitStart + $postsPerPage + 1;

		// Select posts from database
		$tbl_name="ds_posts";

		// Show posts that are not private
		$sql="SELECT * FROM $tbl_name WHERE private=0 AND (content LIKE '%$search%' OR title LIKE '%$search%') ORDER BY id DESC LIMIT $limitStart , $limitEnd;";


		// Query Mysql
		$result=mysql_query($sql);
		$count=mysql_num_rows($result);


		if ($count>0) {
			// Fetch posts
			for($i=0; $i<$count & $i<$postsPerPage; $i++) {

				// Fetch the array
				$row = mysql_fetch_array($result, MYSQL_ASSOC);

				// Set some parameters
				$dateModified = date("m-d-Y",$row["datemodified"]);

				$crtAuthorID = $row["author"];
				$crtPostID = $row["id"];
				$crtPostTitle = $row["title"];
				$crtPostContent = $row["content"];
				$crtAuthorName = $this->getUserName($crtAuthorID);

				// Determine user privilege
				if($_SESSION['myrole']=="admin" || $_SESSION['myid']==$crtAuthorID){
					$actionButtons = <<< ACTION_BUTTONS
						<a href='ds_write.php?id=$crtPostID' class='actionBtn'>
						<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
						</a>
						<a href='ds_deletepost.php?id=$crtPostID' class='actionBtn'>
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
						</a>
ACTION_BUTTONS;
				} else {
					$actionButtons = "";
				}

					echo <<< HOMEPAGE_POSTS
					<div class="container">
					<a href='ds_post.php?id=$crtPostID' class='postTitle'><h1>$crtPostTitle</h1></a>
					<div class="meta">
					Author:&nbsp;
					<a href='ds_user.php?id=$crtAuthorID'>$crtAuthorName</a>&nbsp;
					Date: $dateModified &nbsp;
					$actionButtons
					</div>
					$crtPostContent
					<hr>
					</div>
HOMEPAGE_POSTS;


			}
		} else {
			echo "<div class='meta'>Oops, nothing can be found!</div>";
		}

		// Return if there's a next page
		return ($count>$postsPerPage);
	}


	/**
	* --------------------------------------------------
	* print multiple comments function
	* @param Print format, default = Homepage format
	* @param Page number of the post, default = 1
	* @param How many posts to be shown per page, default = 10
	* @param Filter post by an author's name, default = * (no filtering)
	* @param Filter post by privacy condition, default = 0 (Will NOT get private posts)
	* --------------------------------------------------
	*/
	public function printComments($format="post",$cmtPageNum="1",$commentsPerPage="10",$authorID="*",$postID="*"){

		// Initialize the range for showing posts
		$limitStart = ($cmtPageNum - 1) * $commentsPerPage;
		$limitEnd = $limitStart + $commentsPerPage + 1;

		// Select posts from database
		$tbl_name="ds_comments";

		if($postID!="*" & $format=="post"){
			// Show comments under a post
			$sql="SELECT * FROM $tbl_name WHERE postid='$postID' ORDER BY id DESC LIMIT $limitStart , $limitEnd;";
		} else if($authorID!="*" & $format=="personal"){
			// Show comments by a user
			$sql="SELECT * FROM $tbl_name WHERE authorid='$authorID' ORDER BY id DESC LIMIT $limitStart , $limitEnd;";
		} else {
			// Show all comments
			$sql="SELECT * FROM $tbl_name ORDER BY id DESC LIMIT $limitStart , $limitEnd;";
		}


		// Query Mysql
		$result=mysql_query($sql);
		$count=mysql_num_rows($result);

		if ($count>0) {
			// Fetch posts
			for($i=0; $i<$count & $i<$commentsPerPage; $i++) {

				// Fetch the array
				$row = mysql_fetch_array($result, MYSQL_ASSOC);

				// Set some parameters
				$dateModified   = date("m-d-Y",$row["datemodified"]);
				$crtAuthorID	  = $row["authorid"];
				$crtVisitorName = $row["visitorname"];
				$crtCmtID	      = $row["id"];
				$crtCmtContent  = $row["content"];
				$crtCmtPostID   = $row["postid"];

				// Set display format
				if($crtAuthorID) {
					// a registered member
					$crtAuthorName = $this->getUserName($crtAuthorID);
					$crtAuthorRole = $this->getRole($crtAuthorID);
					$crtAuthorName = "<span class='label label-default'>$crtAuthorRole</span>&nbsp;" .
					"<a href='ds_user.php?id=$crtAuthorID'>".$crtAuthorName."</a>";
				} else {
					// a visitor
					$crtAuthorName = "<span class='label label-default'>visitor</span>&nbsp;" . $crtVisitorName;
				}

				// Determine user privilege
				if($_SESSION['myrole']=="admin"){
					$actionButtons = <<< ACTION_BUTTONS
						&nbsp;<a href='ds_deletecomment.php?id=$crtCmtID' class='actionBtn'>
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
ACTION_BUTTONS;
				} else {
					$actionButtons = "";
				}

				if($format=="post"){
					echo <<< POST_COMMENTS
					<div class="unselectable mediumForm"><div class="meta">
					$crtAuthorName
					$dateModified
					$actionButtons </div>
					$crtCmtContent
					</div>
POST_COMMENTS;
				}

				if($format=="personal"){
					$crtCmtExcerpt = substr($crtCmtContent, 0,40);
					echo <<< PERSONAL_COMMENTS
					<div class="unselectable"><a href="ds_post.php?id=$crtCmtPostID">
					$crtCmtExcerpt </a>
					<div class="meta">
					($dateModified) $actionButtons </div>
					</div>
PERSONAL_COMMENTS;
				}

				if($format=="admin"){
					$crtCmtExcerpt = substr($crtCmtContent, 0,40);
					echo <<< ADMIN_COMMENTS
					<a href="ds_post.php?id=$crtCmtPostID">
					$crtCmtExcerpt </a>
					<div class="unselectable meta">
					$crtAuthorName $dateModified  $actionButtons
					</div>
ADMIN_COMMENTS;
				}

			}
		} else {
			echo <<< NO_COMMENT
			<div class="meta unselectable">
			Oops, there's no comments here at all!
			</div>
NO_COMMENT;
		}

		// Return if there's a next page
		return ($count>$commentsPerPage);
	}

	/**
	* --------------------------------------------------
	* print multiple comments function
	* @param Print format, default = Homepage format
	* @param Page number of the post, default = 1
	* @param How many posts to be shown per page, default = 10
	* @param Filter post by an author's name, default = * (no filtering)
	* @param Filter post by privacy condition, default = 0 (Will NOT get private posts)
	* --------------------------------------------------
	*/
	public function printPictures($pageNum="1"){
		// Picture Range
		$limitStart = ($pageNum-1) * 9;
		$limitEnd = $limitStart + 10;

		$tbl_name="ds_media"; // Table name
		$sql="SELECT * FROM $tbl_name ORDER BY id DESC LIMIT $limitStart, $limitEnd";
		$result=mysql_query($sql);
		$count=mysql_num_rows($result);
		if ($count>0) {
		    // Fetch posts
		    for($x=0; $x<9 and $x<$count; $x++) {
		        //output each post
		        $row = mysql_fetch_array($result, MYSQL_ASSOC);
		        $crtImageUrl = $row["url"];
		        $crtImageId = $row["id"];
		       	if(PRINT_FORMAT!="admin"){
		        echo <<< EOT
		<div class='galleryPic'><a onclick="parent.image('$crtImageUrl')">
		<img src='$crtImageUrl'>
		</a></div>
EOT;
				} else {
				echo "<div class='galleryPic'><a href='ds_deletepic.php?id=$crtImageId'>
				<img src='$crtImageUrl'></a></div>";
				}

		    }
		} else {
			echo "<div class='meta'>Oops, gallery is empty!</div>";
		}
		// Return if there's a next page
		return ($count>9);
	}

}


function showMsg($msg,$page){ //Show Message bar
	session_start();
	$_SESSION['msg'] = $msg;
	header("location:$page");
	exit();
}

function showError($msg,$page){ //Show Error bar
	session_start();
	$_SESSION['error'] = $msg;
	header("location:$page");
	exit();
}

function forceAdmin(){ // Force administrator access
	if($_SESSION['myrole']!="admin"){
		showError("Oops, you are not administrator! You cannot access this page!","index.php");
	}
}

function forceWriter(){ // Force writer access and above
	if($_SESSION['myrole']!="writer"&$_SESSION['myrole']!="admin"){
		showError("Oops, you cannot access this page!","index.php");
	}
}

function validatePwd($str){
 	if($str != stripslashes($str)){
		return 0;
 	}
 	if($str != mysql_real_escape_string($str)){
		return 0;
 	}
	if(strlen($str) > 5 & strlen($str) < 30){
		return 1;
	} else {
		return 0;
	}
}

function validateStr($str){
 	if($str != stripslashes($str)){
		return 0;
 	}
 	if($str != mysql_real_escape_string($str)){
		return 0;
 	}
	if (preg_match("/^[a-zA-Z][0-9a-zA-Z_!$@#^&]{3,30}$/", $str)){
		return 1;
	} else {
		return 0;
	}
}

function validateEmail($email){
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}


function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

?>

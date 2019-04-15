<?php
include "mysqlClass.inc.php";
$con = mysqli_connect($dbhost, $dbuser, $dbpass, $database) or die("can't connect to db, happen in function.php");


function user_pass_check($username, $password)
{
    global $con;	
	$query = "select * from users where username='$username'";
	$result = mysqli_query($con, $query );
		
	if (!$result)
	{
	   die ("user_pass_check() failed. Could not query the database: <br />". mysqli_error($con));
	}
	else{
		$row = mysqli_fetch_row($result);
		if(!$row) 
			return 3; // no user exists
		if(strcmp($row[3],$password))
			return 2; //wrong password
		else 
			return 0; //Checked.
	}	
}

function updateMediaTime($mediaid)
{
    global $con;	
	$query = "	update  media set lastaccesstime=NOW()
   						WHERE '$mediaid' = mediaid
					";
					 // Run the query created above on the database through the connection
    $result = mysqli_query($con, $query );
	if (!$result)
	{
	   die ("updateMediaTime() failed. Could not query the database: <br />". mysqli_error($con));
	}
}

function upload_error($result)
{
	//view erorr description in http://us2.php.net/manual/en/features.file-upload.errors.php
	switch ($result){
	case 1:
		return "UPLOAD_ERR_INI_SIZE";
	case 2:
		return "UPLOAD_ERR_FORM_SIZE";
	case 3:
		return "UPLOAD_ERR_PARTIAL";
	case 4:
		return "UPLOAD_ERR_NO_FILE";
	case 5:
		return "File has already been uploaded";
	case 6:
		return  "Failed to move file from temporary directory";
	case 7:
		return  "Upload file failed";
	}
}

function addContact($username, $contactname)
{
	//You can write your own functions here.
	global $con;

	$query = "SELECT id FROM users WHERE username='$username'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);
	$userid = $row[0];

	// get id of contact username
	$query = "SELECT * FROM users WHERE username='$contactname'";
	$result = mysqli_query($con, $query );
	if (!$result)
	{
	   die ("addContact() failed. Could not query the database: <br />". mysqli_error($con));
	}
	$row = mysqli_fetch_row($result);
	if(!$row) 
		return 1; // no user exists
	$query = "SELECT id FROM users WHERE username='$contactname'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);
	$contactid = $row[0];

	$query = "SELECT * FROM user_contact WHERE userid='$userid' and contactid='$contactid'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);

	if($row)
		return 2; // already a contact

	$query = "INSERT INTO user_contact(userid, contactid) VALUES ('$userid', '$contactid')";
	$result = mysqli_query($con, $query);
	if(!$result){
		return 3;
	}

	$query = "INSERT INTO user_contact(userid, contactid) VALUES ('$contactid', '$userid')";
	$result = mysqli_query($con, $query);
	if($result){
		return 0;
	}
	else {
		return 3;
	}
}

function getContacts($username){
	$query = "SELECT id FROM users WHERE username='$username'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);
	$userid = $row[0];

	$query = "SELECT username, email FROM users INNER JOIN user_contact ON users.id = user_contact.contactid WHERE user_contact.userid='$userid'";
	$result = mysqli_query($con, $query);
	if(!$result){
		return 3;
	}
	return $result;
}

function getMedia($username){
	$query = "SELECT media.title FROM media INNER JOIN upload ON media.mediaid = upload.mediaid INNER JOIN users ON upload.username = users.username WHERE users.username='$username'";
	$result = mysqli_query($con, $query);
	return $result;
}
	
function addPlaylist($username, $playlist)
{
	//You can write your own functions here.
	global $con;

	$query = "SELECT playlist FROM user_playlists WHERE username='$username' and playlist='$playlist'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);

	if(!$row) {

	}

	if($row)
		return 2; // already a contact
}

?>
<?php
session_start();
include_once "function.php";

/******************************************************
*
* upload document from user
*
*******************************************************/

$username=$_SESSION['username'];


//Create Directory if doesn't exist
if(!file_exists('uploads/'))
//	mkdir('uploads/', 0744);
{
	mkdir('uploads/');
	chmod('uploads/', 0755);
}
$dirfile = 'uploads/'.$username.'/';
if(!file_exists($dirfile))
//	mkdir($dirfile, 0744);
{
	mkdir($dirfile);
	chmod($dirfile, 0755);
}
	

	if($_FILES["file"]["error"] > 0 )
	{ $result=$_FILES["file"]["error"];} //error from 1-4
	else
	{
	  $upfile = $dirfile.urlencode($_FILES["file"]["name"]);
	  
	  if(file_exists($upfile))
	  {
	  		$result="5"; //The file has been uploaded.
	  }
	  else{
			if(is_uploaded_file($_FILES["file"]["tmp_name"]))
			{
				if(!move_uploaded_file($_FILES["file"]["tmp_name"],$upfile))
				{
					$result="6"; //Failed to move file from temporary directory
				}
				else /*Successfully upload file*/
				{
					chmod($upfile, 0644);
					//insert into media table
					$insert = "insert into media(
							  mediaid, filename,filepath,type,title,description,category) 
							  values(NULL,
							  	'". urlencode($_FILES["file"]["name"])."',
							  	'$dirfile',
							  	'".$_FILES["file"]["type"]."', 
							  	'".$_POST["title"]."', 
							  	'".$_POST["description"]."', 
							  	'".$_POST["category"]."'
							  )";
					$queryresult = mysqli_query($con, $insert)
						  or die("Insert into Media error in media_upload_process.php " .mysqli_error($con));
					$result="0";
					
					$mediaid = mysqli_insert_id($con);
					//insert into upload table
					$insertUpload="insert into upload(uploadid,username,mediaid) values(NULL,'$username','$mediaid')";
					$queryresult = mysqli_query($con, $insertUpload)
						  or die("Insert into view error in media_upload_process.php " .mysqli_error($con));
				}
			}
			else  
			{
					$result="7"; //upload file failed
			}
		}
	}
	
	//You can process the error code of the $result here.
?>

<meta http-equiv="refresh" content="0;url=browse.php?result=<?php echo $result;?>">

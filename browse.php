<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	session_start();
	include_once "function.php";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media browse</title>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<script type="text/javascript" src="js/jquery-latest.pack.js"></script>
<script type="text/javascript">
function saveDownload(id)
{
	$.post("media_download_process.php",
	{
       id: id,
	},
	function(message) 
    { }
 	);
} 
</script>
</head>

<body>
<h1>Homepage</h1>
<p>Welcome <?php echo $_SESSION['username'];?>.</p>
<?php 
	if (! empty($_SESSION['logged_in']))
	{
		$username = $_SESSION['username'];
    	echo "<p>You can only see this text if you are logged in</p>
    	<a href='logout.php'>Click here to log out</a>";

    	echo "<p>Click <a href='update.php'>here</a> to update your profile information.</p>";

    	echo "<h3>Contacts</h3>";
		$query = "SELECT id FROM users WHERE username='$username'";
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_row($result);
		$userid = $row[0];

		$query = "SELECT username, email FROM users INNER JOIN user_contact ON users.id = user_contact.contactid WHERE user_contact.userid='$userid'";
		$result = mysqli_query($con, $query);
		if(!$result){
			echo "fail";
		}
		else {
			while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
    			echo "username: ".$row[0]."&nbsp; email: ".$row[1];
    			echo "</br>";  
			}
		}


    	echo "<p> Click <a href='add_contact.php'>here</a> to add a contact by username.</p>";
	}
	else
	{
	    echo 'You are not logged in. <a href="index.php">Click here</a> to log in.';
	}

?>

<a href='media_upload.php'  style="color:#FF9900;">Upload File</a>
<div id='upload_result'>
<?php 
	if(isset($_REQUEST['result']) && $_REQUEST['result']!=0)
	{
		
		echo upload_error($_REQUEST['result']);

	}
?>
</div>
<br/><br/>
<?php


	$query = "SELECT * from media"; 
	$result = mysqli_query($con, $query );
	if (!$result)
	{
	   die ("Could not query the media table in the database: <br />". mysqli_error($con));
	}
?>
    
    <div style="background:#339900;color:#FFFFFF; width:150px;">All Uploaded Media</div>
	<table width="50%" cellpadding="0" cellspacing="0">
		<?php
			while ($result_row = mysqli_fetch_row($result))
			{ 
		?>
        <tr valign="top">			
			<td>
					<?php 
						echo $result_row[0];
					?>
			</td>
            <td>
            	<a href="media.php?id=<?php echo $result_row[0];?>" target="_blank"><?php echo $result_row[1];?></a> 
            </td>
            <td>
            	<a href="<?php echo $result_row[2].$result_row[1];?>" target="_blank" onclick="javascript:saveDownload(<?php echo $result_row[0];?>);">Download</a>
            </td>
		</tr>
		<?php
			}
		?>
	</table>

	<br/><br/>
<?php


	$query = "SELECT media.title FROM media INNER JOIN upload ON media.mediaid = upload.mediaid INNER JOIN users ON upload.username = users.username WHERE users.username='$username'";
	$result = mysqli_query($con, $query );
	if (!$result)
	{
	   die ("Could not query the media table in the database: <br />". mysqli_error($con));
	}
?>  
    <div style="background:#339900;color:#FFFFFF; width:150px;">My Uploaded Media</div>
	<table width="50%" cellpadding="0" cellspacing="0">
		<?php
			while ($result_row = mysqli_fetch_row($result))
			{ 
		?>
        <tr valign="top">			
			<td>
					<?php 
						echo $result_row[0];
					?>
			</td>
         </tr>
		<?php
			}
		?>
	</table>



</body>
</html>

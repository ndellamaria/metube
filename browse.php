<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	session_start();
	include_once "function.php";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media browse</title>
<link rel="stylesheet" type="text/css" href="default.css" />
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

<div class="topnav">
  <a class="active" href="browse.php">MeTube</a>
  <input type="text" placeholder="Search..">
  <?php 
	if (! empty($_SESSION['logged_in']))
	{
  		echo "<a href='logout.php'>Logout</a>
  		<a href='update.php'>Profile</a>";
	}
	else {
		echo"<a href='index.php'>Login</a>";
	}
  ?>
</div>

<h1>Browse</h1>
<?php 
	if (! empty($_SESSION['logged_in']))
	{
		$username = $_SESSION['username'];
		echo "<p>Welcome ".$_SESSION['username']."<br/>";
		echo "<a href='media_upload.php'>Upload File</a>";
?>
		<div id='upload_result'>
		<?php if(isset($_REQUEST['result']) && $_REQUEST['result']!=0)
		{
			echo upload_error($_REQUEST['result']);
		}
		?>
		</div>
		<?php } 
		else {
			echo "<p>Please login to upload media.</p>";
		}
		?>
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
	<table width="50%" cellpadding="0" cellspacing="0" style="text-align: center">
		<?php
			while ($result_row = mysqli_fetch_row($result))
			{ 
		?>
		<tr>
			<th>Owner</th>
			<th>Title</th>
			<th>Description</th>
			<th>Category</th>
			<th></th>
		</tr>
        <tr valign="top">			
			<td>
					<?php 
						echo $result_row[8];
					?>
			</td>
            <td>
            	<a href="media.php?id=<?php echo $result_row[0];?>" target="_blank"><?php echo $result_row[5];?></a> 
            </td>
            <td><?php echo $result_row[6];?></td>
            <td><?php echo $result_row[7];?></td>

            <td>
            	<a href="<?php echo $result_row[2].$result_row[1];?>" target="_blank" onclick="javascript:saveDownload(<?php echo $result_row[0];?>);">Download</a>
            </td>
		</tr>
		<?php
			}
		?>
	</table>
</body>
</html>

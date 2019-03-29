<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	session_start();
	include_once "function.php";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media</title>
<script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="default.css" />
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
		echo"<a href='register.php'>Register</a>";
	}
  ?>
</div>

<?php
if(isset($_GET['id'])) {
	$query = "SELECT * FROM media WHERE mediaid='".$_GET['id']."'";
	$result = mysqli_query($con, $query );
	$result_row = mysqli_fetch_row($result);

	updateMediaTime($_GET['id']);

	$filename=$result_row[1];
	$filepath=$result_row[2];
	$type=$result_row[3];

if(isset($_POST['submit'])){
	$username = $_SESSION['username'];
	$mediaid = $_GET['id'];
	$comment = $_POST['comment'];
	$query = "INSERT INTO comments(username, mediaid, comment) VALUES ('$username', '$mediaid', '$comment')";
	$result = mysqli_query($con, $query);

	if($result){
		$smsg = "Comment Created Successfully";
		$mediapath='Location: media.php?id='.$_GET["id"];
		header($mediapath);
	}
	else {
		$fmsg = "Comment Failed".mysqli_error($con);
	}
}

?>

<div class="meta_media">
	<h3><?php echo $result_row[5]?></h3>
	<div class="media_player">
		<?php
			if(substr($type,0,5)=="image") //view image
			{
				echo "<img src='".$filepath.$filename."' width=400px height=325px/>";
			}
			else //view movie
			{
		?>
	    <object id="MediaPlayer" width=320 height=286 classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" standby="Loading Windows Media Player components…" type="application/x-oleobject" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112">

		<param name="filename" value="<?php echo $result_row[2].$result_row[1];  ?>">
		<param name="Showcontrols" value="True">
		<param name="autoStart" value="True">

		<embed type="application/x-mplayer2" src="<?php echo $result_row[2].$result_row[1];  ?>" name="MediaPlayer" width=320 height=240></embed>

		</object>
		<?php } ?>
	</div>
	<div class="meta">
		<p><b>Owner:</b> <?php echo $result_row[8]?></p>
		<p><b>Date Uploaded:</b> <?php echo $result_row[4]?></p>
		<p><b>Category:</b> <?php echo $result_row[7]?></p>
		<p><b>Description:</b> <?php echo $result_row[6]?></p>
		<b><p>Comments:</p></b>
		<?php
			$query = "SELECT * FROM comments WHERE mediaid='".$_GET['id']."'"."ORDER BY commentTime";
			$result = mysqli_query($con, $query);
		?>
		<style type = "text/css">
			#table1 {
				border-collapse: collapse;
				width:30%
				color:#D3D3D3;
				font-size: 15px;
				text-align: left;
			}
			#table1 th, #table1 td {
	      border: 1px solid #ddd;
	      padding: 8px;
	    }
			#table1 th {
				background-color: #e9e9e9;
				padding-top: 12px;
				padding-bottom: 12px;
				text-align: left;
			}

			</style>
		<table id = "table1">
			<tr>
				<th>User</th>
				<th>Comment</th>
			</tr>
			<?php
				while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
			?>
			<tr>
				<td><?php echo $row[1] ?></td>
				<td><?php echo $row[3] ?></td>
			</tr>
			<?php } ?>
			<?php
				if (! empty($_SESSION['logged_in']))
				{
					$mediapath="media.php?id=".$_GET["id"]; ?>
					<form method="POST" action=<?php echo $mediapath ?>>
						<tr>
	  						<td><input name="comment" type="text" placeholder="New comment..."></td>
						</tr>
						<tr>
	    					<td><input name="submit" type="submit" value="Post"><br /></td>
						</tr>
					</form>
				<?php }
			?>
		</table>
	</div>
	<?php if(isset($smsg)){ ?><div role="alert"> <?php echo $smsg; ?> </div><?php } ?>
	<?php if(isset($fmsg)){ ?><div role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
</div>

<?php
}
else
{
?>
<meta http-equiv="refresh" content="0;url=media.php?id=".<?php echo $GET_["id"]; ?>>
<?php
}
?>
</body>
</html>

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
</head>

<body>
<?php
if(isset($_GET['id'])) {
	$query = "SELECT * FROM media WHERE mediaid='".$_GET['id']."'";
	$result = mysqli_query($con, $query );
	$result_row = mysqli_fetch_row($result);
	
	updateMediaTime($_GET['id']);
	
	$filename=$result_row[1];
	$filepath=$result_row[2];
	$type=$result_row[3];
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
		<p>Owner: <?php echo $result_row[8]?></p>
		<p>Date Uploaded: <?php echo $result_row[4]?></p>
		<p>Category: <?php echo $result_row[7]?></p>
		<p>Description: <?php echo $result_row[6]?></p>
	</div>

</div>
              
<?php
}
else
{
?>
<meta http-equiv="refresh" content="0;url=browse.php">
<?php
}
?>
</body>
</html>

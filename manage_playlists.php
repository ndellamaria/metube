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

<?php 
	$username = $_SESSION['username'];
	if(isset($_POST['playlistname'])) {
		$playlistname = $_POST['playlistname'];
		$query = "DELETE FROM user_playlists WHERE playlist='$playlistname' AND username='$username'";
		$result = mysqli_query($con, $query );

		$query = "DELETE FROM playlists WHERE playlistname='$playlistname' AND username='$username'";
		$result = mysqli_query($con, $query );
	}
	if(isset($_POST['mediaid'])) {
		$mediaid = $_POST['mediaid'];
		$query = "DELETE FROM playlists WHERE mediaid='$mediaid' AND username='$username'";
		$result = mysqli_query($con, $query );
		if(!$result){
			echo mysqli_error($con);
		}
	}

?>

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
	$user = $_GET['user'];
	$query = "SELECT playlist FROM user_playlists WHERE username='$user'";
	$result = mysqli_query($con, $query);
	while($row = mysqli_fetch_row($result)){ 
		$playlistname = $row[0]; ?>
		<table>
			<tr>
				<th><?php echo $row[0]; ?></th>
				<th>
					<?php $path = "manage_playlists.php?user=".$_GET['user']; ?>
					<form action=<?php echo $path ?> method="post">
						<input type="hidden" name="playlistname" value="<?php echo $playlistname; ?>">
						<input type="submit" value="Delete Playlist">
					</form><br>
				</th>
			</tr>
			<?php 
				$query = "SELECT media.mediaid, title FROM media INNER JOIN playlists ON media.mediaid=playlists.mediaid WHERE playlists.username='$username' AND playlists.playlist='$playlistname'";
				$titles = mysqli_query($con, $query);
				if(!$titles){
					echo mysqli_error($con);
				}
				while($title = mysqli_fetch_row($titles)) { 
					$mediaid = $title[0]; ?>
					<tr>
						<td><?php echo $title[1]; ?></td>
						<td>
							<?php $path = "manage_playlists.php?user=".$_GET['user']; ?>
							<form action=<?php echo $path ?> method="post">
								<input type="hidden" name="mediaid" value="<?php echo $mediaid; ?>">
								<input type="submit" value="Delete Media">
							</form><br>
						</td>
					</tr>
				<?php } ?>
		</table>
	<?php } ?>

</body>
</html>
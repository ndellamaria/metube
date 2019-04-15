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
	<table align="right">
	<form action="browseFilter.php" method="post">
		<td><input type="text" placeholder="Search.." name="searchwords"></td>
		<td><input type="submit" value="Search" name="search"></td>
</form>
</table>
  <?php
	if (! empty($_SESSION['logged_in']))
	{
  		echo "<a href='logout.php'>Logout</a>
  		<a href='update.php'>Profile</a>";
	}
	else {
		echo"<a href='index.php'>Login</a>";
		echo"<a href='registration.php'>Register</a>";
	}

	if(isset($_POST['search'])){

	}

  ?>
</div>

<h1>Browse</h1>
<?php
	if (! empty($_SESSION['logged_in']))
	{
		$username = $_SESSION['username'];
		echo "<p>Welcome ".$_SESSION['username'];
		echo "<br><a href='media_upload.php'>Upload File</a>";
?>
		<div id='upload_result'>
		<?php if(isset($_REQUEST['result']) && $_REQUEST['result']!=0)
		{
			echo upload_error($_REQUEST['result']);
		}
		?>
		</div>
		<h3>Add new playlist</h3>
		<form action="browse.php" method="post">
			<input name="new_playlist" type="text" placeholder="new playlist..." maxlength="20"> 
			<input type="submit" value="submit">
		</form>
		<h4><a href="manage_playlists.php?user=<?php echo $username;?>" target="_blank">Manage Playlists</a></h4>
		<?php }
		else {
			echo "<p>Please login to upload media.</p>";
		}
		?>
<br/><br/>

<?php
	if(isset($_POST['type'])) {
		$type = $_POST['type'];
		if($type == 'all'){
			$type_query = "category IN ('image','video','audio')";
		}
		else if($type == 'images') {
			$type_query = "category='image'";
		}
		else if($type == 'videos'){
			$type_query = "category='video'";
		}
		else if($type == 'audio'){
			$type_query = "category='audio'";
		}
		else{
			$type_query = "category IN ('image','video','audio')";
		}
	}
	else {
		$type_query = "category IN ('image','video','audio')";
	}

	if(isset($_POST['playlist'])){
		$playlist = $_POST['playlist'];
		if($playlist == 'all'){
			$playlist_query = "SELECT * from media WHERE ".$type_query;
		}
		else {
			$playlist_query = "SELECT * FROM media INNER JOIN playlists ON media.mediaid=playlists.mediaid WHERE playlists.playlist='$playlist' AND username='$username' AND ".$type_query;
		}
	}
	else{
		$playlist_query = "SELECT * from media WHERE ".$type_query;
	}
	$result = mysqli_query($con, $playlist_query);
?>
   
<?php
	if(isset($_POST['favorite'])) {
		$mediaid = $_POST['favorite'];
		$query = "INSERT INTO playlists(playlist,username, mediaid) VALUES('favorites', '$username', '$mediaid')";
		$favs = mysqli_query($con, $query );
	}
	if(isset($_POST['unfavorite'])) {
		$mediaid = $_POST['unfavorite'];
		$query = "DELETE FROM playlists WHERE playlist='favorites' AND username='$username' AND mediaid='$mediaid'";
		$favs = mysqli_query($con, $query );
	}
	if(isset($_POST['new_playlist'])){
		$new_playlist = $_POST['new_playlist'];
		$query = "SELECT playlist FROM user_playlists WHERE username='$username' and playlist='$new_playlist'";
		$playlist_result = mysqli_query($con, $query);
		$row = mysqli_fetch_row($playlist_result);
		if(!$row) {
			$query = "INSERT into user_playlists(playlist, username) VALUES('$new_playlist', '$username')";
			$new_playlist_result = mysqli_query($con, $query);
		}

		if($row) {
			echo 'You already have a playlist with that name.';
		}
	}
	if(isset($_POST['add_to_playlist'])) {
		$mediaid = $_POST['mediaAddToPlaylist'];
		$addToPlaylist = $_POST['add_to_playlist'];
		$query = "SELECT * FROM playlists WHERE username='$username' and playlist='$addToPlaylist' and mediaid='$mediaid'";
		$add_to_playlist_result = mysqli_query($con, $query);
		$row = mysqli_fetch_row($add_to_playlist_result);
		if(!$row) {
			$query = "INSERT INTO playlists(playlist,username, mediaid) VALUES('$addToPlaylist', '$username', '$mediaid')";
			$add_to_playlist_result = mysqli_query($con, $query);
		}

		if($row){
			echo 'This media is already part of that playlist';
		}
	}
?>
    <h3>Filters</h3>
    <table>
    <tr>
    	<th><h4>Category</h4></th>
    	<?php if (! empty($_SESSION['logged_in'])) { ?>
			<th><h4>Playlist</h4></th> <?php } ?>
    	<th></th>
    </tr>
    <tr>
	    <td>
	    	<form action="browse.php" method="post">
		  		<select name="type" type="text">
		    		<option value="all" selected="selected">All</option>
		    		<option value="images">Images</option>
		    		<option value="videos">Videos</option>
		    		<option value="audio">Audio</option>
		  		</select>
		</td>
  	<?php 
	if (! empty($_SESSION['logged_in']))
	{ ?>
		<td>
		  	<form action="browse.php" method="post">
		  <?php 
			$query = "SELECT * FROM user_playlists where username='$username'";
			$playlist_result = mysqli_query($con, $query); ?>
				<select name="playlist">
					<option value="all" selected="selected">All</option>
					<option value="favorites">Favorites</option>
				<?php while ($playlist_row = mysqli_fetch_row($playlist_result)){ ?>
					<option value="<?php echo $playlist_row[2]; ?>"> <?php echo $playlist_row[2]; ?> </option><br>;
			<?php } ?>
				</select>
		</td>
	<?php } ?>
		<td><input type="submit" value="submit"></td>
		</form>
	</tr>
	</table>

    <div class="all_media">
		<?php
			//print $result;
			while ($result_row = mysqli_fetch_row($result))
			{
		?>

		<div class="media_box">
			<?php
				$mediaid = $result_row[0];
				$filename=$result_row[1];
				$filepath=$result_row[2];
				$type=$result_row[3];
				if(substr($type,0,5)=="image") //view image
				{
					echo "<img src='".$filepath.$filename."' height=200 width=300/>";
				}
				else //view movie
				{
			?>
		    		<object id="MediaPlayer" width=300 height=200 classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" standby="Loading Windows Media Player components…" type="application/x-oleobject" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112">

					<param name="filename" value="<?php echo $result_row[2].$result_row[1];  ?>">
					<param name="Showcontrols" value="True">
					<param name="autoStart" value="True">

					<embed type="application/x-mplayer2" src="<?php echo $result_row[2].$result_row[1];  ?>" name="MediaPlayer" width=320 height=200></embed>

					</object>
			<?php } ?>
			<h4><a href="media.php?id=<?php echo $result_row[0];?>" target="_blank"><?php echo $result_row[5];?></a></h4> 
			<?php
			if (! empty($_SESSION['logged_in']))
			{ 
				$query = "SELECT COUNT(*) FROM playlists WHERE playlist='favorites' AND username='$username' and mediaid='$mediaid'";
				$favs = mysqli_query($con, $query );
				$favs_row = mysqli_fetch_row($favs);
				if($favs_row[0] == 0){ ?>
					<form action="browse.php" method="post">
						<input type="hidden" name="favorite" value="<?php echo $mediaid; ?>">
						<input type="submit" value="Favorite">
					</form><br>
				<?php } 
				else { ?>
					<form action="browse.php" method="post">
						<input type="hidden" name="unfavorite" value="<?php echo $mediaid; ?>">
						<input type="submit" value="Unfavorite">
					</form><br>
				<?php } ?>
				<h4>Add to playlist:</h4>
				<?php 
					$query = "SELECT * FROM user_playlists where username='$username'";
					$addToPlaylist_result = mysqli_query($con, $query); ?>
					<form action="browse.php" method="post">
						<input type="hidden" name="mediaAddToPlaylist" value="<?php echo $mediaid; ?>">
						<select name="add_to_playlist">
							<?php while ($addToPlaylist_row = mysqli_fetch_row($addToPlaylist_result)){ ?>
								<option value="<?php echo $addToPlaylist_row[2]; ?>"> <?php echo $addToPlaylist_row[2]; ?> </option><br>;
							<?php } ?>
						</select>
						<input type="submit" value="submit">
					</form>
			<?php } ?>
			<a href="<?php echo $result_row[2].$result_row[1];?>" target="_blank" onclick="javascript:saveDownload(<?php echo $result_row[0];?>);">Download</a>
		</div>
		<?php } ?>
	</div>
</body>
</html>
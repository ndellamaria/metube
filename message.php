<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	session_start();
	include_once "function.php";
?>	
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Message</title>
<script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="default.css" />
</head>

<body>
<div class="topnav">
  <a class="active" href="browse.php">MeTube</a>
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
	if(isset($_POST['submit'])){
		$username = $_SESSION['username'];
		$convid = $_GET['id'];
		$msg = $_POST['message'];
		$query = "INSERT INTO messages(convid, msg, sender) VALUES ('$convid', '$msg', '$username')";
		$result = mysqli_query($con, $query);

		if($result){
			$smsg = "Message Created Successfully";
			$msgpath='Location: message.php?id='.$_GET["id"];
			header($msgpath);
		}
		else {
			$fmsg = "Message Failed".mysqli_error($con);
		}
	}
?>
<?php
	$convid = $_GET['id']; 
	$query = "SELECT userA, userB FROM conversations WHERE conversationid='$convid'";
	$users_result = mysqli_query($con, $query);
	$user_row = mysqli_fetch_row($users_result);
	$userA = $user_row[0];
	$userB = $user_row[1];
	$query = "SELECT * FROM messages WHERE convid='".$_GET['id']."'"."ORDER BY timeSent";
	$result = mysqli_query($con, $query);
?>

<h4>Messages between <?php echo $userA." and ".$userB; ?></h4>
<table>
	<tr>
		<th>Username</th>
		<th>Message</th>
	</tr>

	<?php
		while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
	?>
		<tr>
			<td><?php echo $row[4] ?></td>
			<td><?php echo $row[2] ?></td>
		</tr>
		<?php } ?>
		<?php 
			$msgpath="message.php?id=".$_GET["id"]; ?> 
				<form method="POST" action=<?php echo $msgpath ?>>
					<tr>
						<td></td>
  						<td><input name="message" type="text" placeholder="New message (max 200 characters)..." maxlength="200"><br>
  							<input name="submit" type="submit" value="Post"></td>
					</tr>
				</form>
</table>
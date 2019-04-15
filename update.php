<?php
session_start();

include_once "function.php";
?>

<head> 
<title>Profile</title>
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
</body>

<?php
if(isset($_POST['submit'])) {
	if($_POST['username'] == "" || $_POST['email'] == "" || $_POST['password'] == "") {
		$update_error = "Please fill in fields.";
	}
	else {
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];

		if($username == $_SESSION['username']) {
			$query = "UPDATE users SET email='$email', password='$password' WHERE username='$username'";
			$result = mysqli_query($con, $query);

			if($result){
				$smsg = "User Updated Successfully";
				$_SESSION['username']=$_POST['username'];
			}
			else {
				$fmsg = "User Update Failed".mysqli_error($con);
			}
		}
		else {
			$query = "select * from users where username='$username'";
			$result = mysqli_query($con, $query);
			$row = mysqli_fetch_row($result);
			if($row){
				$update_error = "Sorry, that username is already taken. Please choose a different one.";
			}
		}
	}
}

?>

<h1>My Profile</h1>
<h3>User Info</h3>
<form method="POST" action="<?php echo "update.php"; ?>">

<?php if(isset($smsg)){ ?><div role="alert"> <?php echo $smsg; ?> </div><?php } ?>
<?php if(isset($fmsg)){ ?><div role="alert"> <?php echo $fmsg; ?> </div><?php } ?>

<?php 
	$_susername = $_SESSION['username'];
	$query = "select * from users where username='$_susername'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);
	$_semail = $row[2];
	$_spassword = $row[3];
?>

<table width="100%">
	<tr>
		<td  width="20%">Username (max 15 characters):</td>
		<td width="80%"><input class="text"  type="text" name="username" maxlength="15" value="<?php echo $_SESSION['username']; ?>"><br /></td>
	</tr>
	<tr>
		<td  width="20%">Email:</td>
		<td width="80%"><input class="text"  type="text" name="email" maxlength="20" value="<?php echo $_semail; ?>"><br /></td>
	</tr>
	<tr>
		<td  width="20%">Password (max 15 characters):</td>
		<td width="80%"><input class="text"  type="password" name="password" maxlength="15" value="<?php echo $_spassword; ?>"><br /></td>
	</tr>
	<tr>
    
		<td><input name="submit" type="submit" value="Update"><br /></td>
	</tr>
</table>
</form>

<div class="my_contacts">
	<?php 
		echo "<h3>Contacts</h3>";
		$query = "SELECT id FROM users WHERE username='$_susername'";
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_row($result);
		$userid = $row[0];

		$query = "SELECT username, email FROM users INNER JOIN user_contact ON users.id = user_contact.contactid WHERE user_contact.userid='$userid'";
		$result = mysqli_query($con, $query);

		if(!$result){
			echo "fail";
		}
		else { 
	?>
		<table style="width:30%">
			<tr>
				<td>Username</td>
				<td>Email</td>
				<td>Message</td>
			</tr>
		<?php
		while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
		?>
			<?php
				$conv_query = "SELECT conversationID FROM conversations WHERE (userA='$_susername' AND userB='$row[0]') OR (userB='$_susername' AND userA='$row[0]')";
				$conv_result = mysqli_query($con, $conv_query);
				$conv_row = mysqli_fetch_row($conv_result);
				$convid = $conv_row[0];
			?>

			<tr>
				<td><?php echo $row[0] ?></td>
				<td><?php echo $row[1] ?></td>
				<td><a href="message.php?id=<?php echo $convid;?>" target="_blank"">Message</a></td>
			</tr>
		<?php } ?>
		</table>
		<?php } ?>
		

	<?php
    	echo "<p> Click <a href='add_contact.php'>here</a> to add a contact by username.</p>";
    ?>

</div>
<div class="my_uploads">
	<h3>My Media</h3>

	<?php
		$query = "SELECT * FROM media INNER JOIN upload ON media.mediaid = upload.mediaid INNER JOIN users ON upload.username = users.username WHERE users.username='$_susername'";
		$result = mysqli_query($con, $query );
		if (!$result)
		{
		   die ("Could not query the media table in the database: <br />". mysqli_error($con));
		}
	?>  

	<table width="50%" cellpadding="0" cellspacing="0" style="text-align: center">
		<tr>
			<th>Title</th>
			<th>Description</th>
			<th>Category</th>
			<th></th>
		</tr>
		<?php
			while ($result_row = mysqli_fetch_row($result))
			{ 
		?>
        <tr valign="top">			
			<td>
					<h4><a href="media.php?id=<?php echo $result_row[0];?>" target="_blank"><?php echo $result_row[5];?></a></h4>
			</td>
			<td>
					<?php 
						echo $result_row[6];
					?>
			</td>
			<td>
					<?php 
						echo $result_row[7];
					?>
			</td>
         </tr>
		<?php
			}
		?>
	</table>

</div>

<form action="browse.php"><input name="home" type="submit" value="Cancel"></form>

<?php
  if(isset($update_error))
   {  echo "<div>".$update_error."</div>";}
?>

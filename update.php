<?php
session_start();

include_once "function.php";

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

<h1>User Info</h1>
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
		<td  width="20%">Username:</td>
		<td width="80%"><input class="text"  type="text" name="username" value="<?php echo $_SESSION['username']; ?>"><br /></td>
	</tr>
	<tr>
		<td  width="20%">Email:</td>
		<td width="80%"><input class="text"  type="text" name="email" value="<?php echo $_semail; ?>"><br /></td>
	</tr>
	<tr>
		<td  width="20%">Password:</td>
		<td width="80%"><input class="text"  type="password" name="password" value="<?php echo $_spassword; ?>"><br /></td>
	</tr>
	<tr>
    
		<td><input name="submit" type="submit" value="Update"><br /></td>
	</tr>
</table>
</form>

<form action="browse.php"><input name="home" type="submit" value="Cancel"></form>

<?php
  if(isset($update_error))
   {  echo "<div>".$update_error."</div>";}
?>

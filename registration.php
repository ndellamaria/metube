<link rel="stylesheet" type="text/css" href="css/default.css" />
<?php
session_start();

include_once "function.php";

if(isset($_POST['submit'])) {
		if($_POST['username'] == "" || $_POST['email'] == "" || $_POST['password'] == "") {
			$login_error = "One or more fields are missing.";
		}
		else {
			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = $_POST['password'];

			$query = "INSERT INTO users(username, password, email) VALUES ('$username', '$password', '$email')";
			$result = mysqli_query($con, $query);

			if($result){
				$smsg = "User Created Successfully";
			}
			else {
				$fmsg = "User Registration Failed".mysqli_error($con);
			}
		}
}

?>

<h1>Registration</h1>
<form method="POST" action="<?php echo "registration.php"; ?>">

<?php if(isset($smsg)){ ?><div role="alert"> <?php echo $smsg; ?> </div><?php } ?>
<?php if(isset($fmsg)){ ?><div role="alert"> <?php echo $fmsg; ?> </div><?php } ?>

<table width="100%">
	<tr>
		<td  width="20%">Username:</td>
		<td width="80%"><input class="text"  type="text" name="username"><br /></td>
	</tr>
	<tr>
		<td  width="20%">Email:</td>
		<td width="80%"><input class="text"  type="text" name="email"><br /></td>
	</tr>
	<tr>
		<td  width="20%">Password:</td>
		<td width="80%"><input class="text"  type="password" name="password"><br /></td>
	</tr>
	<tr>
    
		<td><input name="submit" type="submit" value="Register"><br /></td>
	</tr>
</table>
</form>

<p>Already a user?</p>
<form action="index.php"><input name="login" type="submit" value="Login Here"></form>

<?php
  if(isset($login_error))
   {  echo "<div>".$login_error."</div>";}
?>

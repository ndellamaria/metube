<head> 
<title>Registration</title>
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
		echo"<a href='registeration.php'>Register</a>";
	}
  ?>
</div>
</body>

<?php
session_start();

include_once "function.php";

if(isset($_POST['submit'])) {
		if($_POST['username'] == "" || $_POST['email'] == "" || $_POST['password'] == "") {
			$login_error = "One or more fields are missing.";
		}
		else {
			$username = $_POST['username'];
			$query = "SELECT * FROM users WHERE username='$username'";
			$result = mysqli_query($con, $query);
			$row = mysqli_fetch_row($result);
			if ($row) {
				$login_error = "That username exists already. Please choose a new one.";
			}
			else {
				$email = $_POST['email'];
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	  				$login_error = "Invalid email format";
				}
				else {

					$password = $_POST['password'];
					$confirm_password = $_POST['confirm_password'];

					if($password != $confirm_password){
						$login_error = "Passwords do not match.";
					}
					else {
						$query = "INSERT INTO users(username, password, email) VALUES ('$username', '$password', '$email')";
						$result = mysqli_query($con, $query);

						if($result){
							$smsg = "User Created Successfully";
							$_SESSION['username']=$_POST['username']; //Set the $_SESSION['username']
							$_SESSION['logged_in']=1;
							header('Location: browse.php');
						}
						else {
							$fmsg = "User Registration Failed".mysqli_error($con);
						}
					}
				}
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
		<td  width="20%">Username (max 15 characters):</td>
		<td width="80%"><input class="text"  maxlength="15" type="text" name="username"><br /></td>
	</tr>
	<tr>
		<td  width="20%">Email:</td>
		<td width="80%"><input class="text"  type="text" name="email"><br /></td>
	</tr>
	<tr>
		<td  width="20%">Password (max 10 characters):</td>
		<td width="80%"><input class="text"  maxlength="10" type="password" name="password"><br /></td>
	</tr>
	<tr>
		<td  width="20%">Confirm Password (max 10 characters):</td>
		<td width="80%"><input class="text"  maxlength="10" type="password" name="confirm_password"><br /></td>
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

<head> 
<title>Login</title>
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
		echo"<a href='registration.php'>Register</a>";
	}
  ?>
</div>
</body>

<h1>Welcome to MeTube</h1>
<h3>Please login or register to continue.</h3>

<?php
session_start();

include_once "function.php";

if(isset($_POST['submit'])) {
		if($_POST['username'] == "" || $_POST['password'] == "") {
			$login_error = "One or more fields are missing.";
		}
		else {
			$check = user_pass_check($_POST['username'],$_POST['password']); // Call functions from function.php
			if($check == 1) {
				$login_error = "User ".$_POST['username']." not found.";
			}
			elseif($check==2) {
				$login_error = "Incorrect password.";
			}
			else if($check==3){
				$login_error = "Unregistered username.";
			}	
			else if($check==0){
				$_SESSION['username']=$_POST['username']; //Set the $_SESSION['username']
				$_SESSION['logged_in']=1;
				header('Location: browse.php');
			}
		}
}


 
?>
	<form method="post" action="<?php echo "index.php"; ?>">

	<table width="100%">
		<tr>
			<td  width="20%">Username:</td>
			<td width="80%"><input class="text"  type="text" name="username" maxlength="15"><br /></td>
		</tr>
		<tr>
			<td  width="20%">Password:</td>
			<td width="80%"><input class="text"  type="password" name="password" maxlength="15"><br /></td>
		</tr>
		<tr>
        
			<td><input name="submit" type="submit" value="Login"><input name="reset" type="reset" value="Reset"><br /></td>
		</tr>
	</table>
	</form>

	<p>Not a user? <a href=registration.php>Click here to register</a></p>

<?php
  if(isset($login_error))
   {  
   	echo "<div id='passwd_result'>".$login_error."</div>";
	if($check==3) {
		echo "<a href=registration.php>Click here to register</a>";
	}
	}
?>

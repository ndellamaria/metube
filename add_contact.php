<?php
session_start();

include_once "function.php";

?>

<head> 
<title>Add Contact</title>
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
$username = $_SESSION['username'];

if(isset($_POST['submit'])) {
		if($_POST['contactname'] == "") {
			$contact_error = "Please enter a contact username.";
		}
		else {
			$contactname = $_POST['contactname'];
			$check = addContact($_SESSION['username'], $contactname);

			if($check == 1) {
				$contact_error = "User ".$_POST['contactname']." not found.";
			}
			elseif($check==2) {
				$contact_error = "You already have ".$contactname." as a contact.";
			}
			else if($check==3){
				$contact_error = "Some other error.";
			}	
			else if($check==0){
				echo "Contact created successfully";
				echo $username;
				echo $contactname;
				$query = "INSERT INTO conversations(userA, userB) VALUES('$username', '$contactname')";
				$result = mysqli_query($con, $query);
				if (!$result){
					echo "error";
					echo mysqli_error($con);
				}
				else {
					echo "success";
				}
			}
		}
}


 
?>
	<form method="post" action="<?php echo "add_contact.php"; ?>">

	<table width="100%">
		<tr>
			<td  width="20%">Contact Username:</td>
			<td width="80%"><input class="text"  type="text" name="contactname" maxlength="15"><br /></td>
		</tr>
        <tr>
		<td><input name="submit" type="submit" value="Submit"><br /></td>
		</tr>
	</table>
	</form>

	<a href="browse.php">Home</a>

<?php
  if(isset($contact_error))
   {  
   	echo "<div>".$contact_error."</div>";
	}
//SELECT id, username, email FROM users INNER JOIN user_contact ON users.id = user_contact.contactid WHERE user_contact.userid='3';
?>

<?php
session_start();

include_once "function.php";

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
			}
		}
}


 
?>
	<form method="post" action="<?php echo "add_contact.php"; ?>">

	<table width="100%">
		<tr>
			<td  width="20%">Contact Username:</td>
			<td width="80%"><input class="text"  type="text" name="contactname"><br /></td>
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

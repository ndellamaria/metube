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
		echo"<a href='registration.php'>Register</a>";
	}
  ?>
</div>
</body>


<?php
session_start();
session_destroy();
echo '<div>You have been logged out. <a href="index.php">Login</a> </div>';
?>

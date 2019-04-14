<!DOCTYPE html>
<?php include '../src/master.php'; 

	if (isset($_POST))
	{

		if (isset($_POST['email'])
			&& $_POST['email'] != ""
			&& isset($_POST['passwd'])
			&& $_POST['passwd'] != ""
			&& isset($_POST['submit'])
			&& $_POST['submit'] === "OK")
		{
			new_account();
		}
		unset($_POST);
	}
?>
<html>
<head>
	<title>42 Book Exchange: Register</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php include '../inc/header.php' ?>
	<?php include '../inc/navbar.php' ?>
	<?php handle_messages_html(); ?>
	<!-- Begin Register Main Body -->
	<h1>Account Registration</h1>
	<form method="POST" action="register.php">
		<label>Intra login:</label><br>
		<input type="text" name="email" autocomplete="off">
		<br>
		<label>Password:</label><br>
		<input type="password" name="passwd">
		<br>
		<br>
		<input type="submit" name="submit" value="OK">
	</form>
	<br>
	<p>
		Existing user? <a href="login.php">Login</a>.
	</p>
	<!-- End Register Main Body -->
	<?php include '../inc/footer.php' ?>
</body>
</html>

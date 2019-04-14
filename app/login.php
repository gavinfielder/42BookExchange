<!DOCTYPE html>
<?php include_once '../src/master.php' ?>
<?php
	if (isset($_POST))
	{

		if (isset($_POST['email'])
			&& isset($_POST['passwd'])
			&& isset($_POST['submit'])
			&& $_POST['submit'] === "OK")
		{
			login();
		}
		unset($_POST);
	}

?>
<html>
<head>
	<title>42 Book Exchange: Login</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php include '../inc/header.php' ?>
	<?php include '../inc/navbar.php' ?>
	<?php handle_messages_html(); ?>
	<!-- Begin Login Main Body -->
	<h1>Account Login</h1>
	<form method="POST" action="login.php">
		<label>Intra login:</label><br>
		<input type="text" name="email">
		<br>
		<label>Password:</label><br>
		<input type="password" name="passwd">
		<br>
		<br>
		<input type="submit" name="submit" value="OK">
	</form>
	<br>
	<p>
		New user? <a href="register.php">Register Here</a>.
	</p>
	<!-- End Login Main Body -->
	<?php include '../inc/footer.php' ?>
</body>
</html>

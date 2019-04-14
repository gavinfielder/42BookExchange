<!DOCTYPE html>
<?php include_once '../src/master.php'; 

	if (logged_on_user() && isset($_GET['action']))
	{
		$action = $_GET['action'];
		if ($action == 'change_password'
			&& isset($_POST['old_pw'])
			&& isset($_POST['new_pw'])
			&& $_POST['new_pw'] != ""
			&& isset($_POST['submit'])
			&& $_POST['submit'] == 'OK')
		{
			change_password(logged_on_user(), $_POST['old_pw'], $_POST['new_pw']);
		}
		elseif ($action = 'set_custom_message'
			&& isset($_POST['message']))
		{
			set_custom_message(logged_on_user(), $_POST['message']);
		}
		else
		{
			message_to_user('Improper Request.');
			redirect('account.php');
		}
		unset($_POST['action']);
	}

?>
<html>
<head>
	<title>User Account</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php include '../inc/header.php' ?>
	<?php include '../inc/navbar.php' ?>
	<?php handle_messages_html(); ?>
	<!-- Begin Account Main Body -->
	<?php
		if (logged_on_user())
			include_once $_SERVER['DOCUMENT_ROOT'] . '/../inc/accountpanel.php';
		else
			echo "<p>You must be logged in to change your account details.</p><br>";
	?>
	<!-- end Account Main Body -->
	<?php include '../inc/footer.php' ?>
</body>
</html>

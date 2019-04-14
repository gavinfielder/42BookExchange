<!DOCTYPE html>
<?php include_once '../src/master.php'; 

	if (admin_authenticate() && isset($_GET['action']))
	{
		$action = $_GET['action'];
		if ($action == 'remove_user'
			&& isset($_POST['user_to_remove'])
			&& $_POST['user_to_remove'] != ""
			&& isset($_POST['submit'])
			&& $_POST['submit'] == 'OK')
			remove_user($_POST['user_to_remove']);
		elseif ($action == 'make_user_admin'
			&& isset($_POST['user_to_make_admin'])
			&& $_POST['user_to_make_admin'] != ""
			&& isset($_POST['submit'])
			&& $_POST['submit'] == 'OK'
			&& isset($_POST['set_to'])
			&& ($_POST['set_to'] == '0' || $_POST['set_to'] == '1'))
			make_user_admin($_POST['user_to_make_admin'], $_POST['set_to']);
		else
		{
			message_to_user('Improper Request.');
			redirect('admin.php');
		}
		unset($_POST['action']);
	}

?>
<html>
<head>
	<title>Admin Panel</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php include '../inc/header.php' ?>
	<?php include '../inc/navbar.php' ?>
	<?php handle_messages_html(); ?>
	<!-- Begin Admin Main Body -->
	<?php
		if (admin_authenticate())
			include_once $_SERVER['DOCUMENT_ROOT'] . '/../inc/adminpanel.php';
		else
			echo "<p>You are not authorized to access this page.</p><br>";
	?>
	<!-- end Admin Main Body -->
	<?php include '../inc/footer.php' ?>
</body>
</html>

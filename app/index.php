<!DOCTYPE html>
<?php include_once '../src/master.php';
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		if ($action == 'remove-listing' && isset($_GET['id']))
		{
			$book = get_entry($_GET['id']);
			if (logged_on_user() == $book['owner'] || admin_authenticate())
			{
				remove_entry($_GET['id']);
				message_to_user("Successfully removed listing!");
				redirect('index.php');
			}
		}
		elseif ($action == 'add-to-basket' && isset($_GET['id']))
		{
			$book = get_entry($_GET['id']);
			if (logged_on_user() != $book['owner']
				&& $book['count'] > 0)
			{
				add_to_basket($_GET['id']);
				message_to_user("Added " . $book['name'] . " to basket!");
				//header('Refresh:0;url=index.php');
				redirect('index.php');
			}
		}
		else
		{
			message_to_user("action not understood.");
		}
		unset($_GET['action']);
	}
?>
<html>
<head>
	<title>42 Book Exchange</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php include '../inc/header.php' ?>
	<?php include '../inc/navbar.php' ?>
	<?php include '../inc/sidebar.php' ?>
	<?php handle_messages_html(); ?>
	<!-- Begin Index Main Body -->
	<h1>Library<?php if (isset($_GET['tag'])) { echo ": " . $_GET['tag']; } ?></h1>
	<?php
		if (isset($_GET['tag']))
			$books = get_all_with_tag($_GET['tag']);
		else
			$books = get_database();
		$html = list_book_array($books);
		echo $html;
	?>
	<!-- End Index Main Body -->
	<?php include '../inc/footer.php' ?>
</body>
</html>

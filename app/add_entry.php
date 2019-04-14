<!DOCTYPE html>
<?php include_once '../src/master.php'; 

	if (isset($_GET))
	{
		if (isset($_GET)
			&& isset($_GET["name"])
			&& isset($_GET["descr"])
			&& isset($_GET["auth"])
			&& isset($_GET["submit"])
			&& $_GET["submit"] == "OK"
			&& isset($_GET["count"]))
			create_entry();
		unset($_GET);
	}

?>

<html>
<head>
	<title>Add Book</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php include '../inc/header.php' ?>
	<?php include '../inc/navbar.php' ?>
	<?php handle_messages_html(); ?>
	<!-- Begin Add Entry Main Body -->
	<h1>Add Book Listing</h1>
	<?php
		if (user_is_logged_in())
			include_once $_SERVER['DOCUMENT_ROOT'] . '/../inc/add_entry_panel.php';
		else
			echo "<p>You must be logged in to add a book.</p><br>";
	?>
	<!-- end Add Entry Main Body -->
	<?php include '../inc/footer.php' ?>
</body>
</html>

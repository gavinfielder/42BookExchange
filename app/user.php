<!DOCTYPE html>
<?php include_once '../src/master.php'; 
	function this_username()
	{
		if (isset($_GET['id']))
			return $_GET['id'];
		else return "";
	}

?>
<html>
<head>
<title>User: <?php echo this_username(); ?></title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php include '../inc/header.php' ?>
	<?php include '../inc/navbar.php' ?>
	<?php handle_messages_html(); ?>
	<!-- Begin User Main Body -->
	<h1>User: <?php echo this_username() ?></h1>
	<?php
	if (isset($_GET['id']))
	{
		$account = get_account(load_account_data(), $_GET['id']);
		if (!$account)
			echo "<p>No such user.</p>";
		else
		{
			echo "<div class=user_info>";
			echo "<div class=user_custom>" . $account['custom'] . "</div>\n";
			echo "<span class=stats>Number of books sold: " . $account['num_sold'] . "</span><br>\n";
			echo "<span class=stats>Number of books bought: " . $account['num_bought'] . "</span><br>\n";
			echo "</div>";
		}
	}
	?>
	<h2><?php echo this_username() ?>'s Listings</h2>
		<?php
		$my_books = array();
		$books = get_database();
		foreach ($books as $book)
		{
			if ($book['owner'] == this_username())
				$my_books[] = $book;
		}
		$html = list_book_array($my_books);
		echo $html;
		?>
	<!-- End User Main Body -->
	<?php include '../inc/footer.php' ?>
</body>
</html>

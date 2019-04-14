<!DOCTYPE html>
<?php session_start(); ?>
<?php include '../src/master.php';

	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		if ($action == 'confirm')
		{
			if (!logged_on_user())
			{
				message_to_user("You must be logged in to place orders");
				redirect('basket.php');
			}
			validate_basket();
			if (!(isset($_SESSION['basket']) && count($_SESSION['basket']) > 0))
			{
				message_to_user("You cannot place an order as your basket is empty");
				redirect('basket.php');
			}
			$requested = 0;
			foreach ($_SESSION['basket'] as $id)
				$requested += new_order($id);
			message_to_user("Successfully requested " . $requested . " available books.");
			redirect('index.php');
		}
		elseif ($action == 'remove' && isset($_GET['id']))
		{
			remove_from_basket($_GET['id']);
			message_to_user("Book removed from basket.");
			redirect('basket.php');
		}
		unset($_GET['action']);
	}

?>
<html>
<head>
	<title>My Basket</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php include '../inc/header.php' ?>
	<?php include '../inc/navbar.php' ?>
	<?php handle_messages_html(); ?>
	<!-- Begin Basket Main Body -->
	<h1>My Basket</h1>
	<?php
		if (isset($_SESSION['basket']) && count($_SESSION['basket']) > 0)
		{
			$html = list_basket();
			echo "<div id=basket_div>" . $html . "</div>";
			echo "<div id=basket_summary>Total: " . money_format("$%i", get_basket_total()) . "</div>";
			echo "<span id=confirm_order><a href='basket.php?action=confirm'>Place Order</a></span>";
		}
		else
		{
			echo "<p>There is currently nothing in your basket. Try adding some items <a href='index.php'>here</a></p>";
		}
	?>
	<!-- End Basket Main Body -->
	<?php include '../inc/footer.php' ?>
</body>
</html>


<!DOCTYPE html>
<?php include_once '../src/master.php'; 

	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		if ($action == 'remove'
			&& isset($_GET['order_id'])
			&& isset($_GET['user']))
		{
			$user = $_GET['user'];
			$order_id = $_GET['order_id'];
			$order = get_order($user, $order_id);
			$book = get_entry($order['book_id']);
			if (logged_on_user() != $book['owner'])
			{
				message_to_user("You can't remove this order because you do not own this book.");
				redirect('orders.php');
			}
			remove_order($user, $order_id, true);
			message_to_user("Order successfully marked as filled.");
			redirect('orders.php');
		}
		else if ($action == "cancel")
		{
			$user = $_GET['user'];
			$order_id = $_GET['order_id'];
			$order = get_order($user, $order_id);
			$book = get_entry($order['book_id']);
			if (logged_on_user() != $book['owner'])
			{
				message_to_user("You can't remove this order because you do not own this book.");
				redirect('orders.php');
			}
			remove_order($user, $order_id, false);
			message_to_user("Order successfully cancelled.");
			redirect('orders.php');
		}
		unset($_GET['action']);
	}

?>

<html>
<head>
	<title>My Orders</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php include '../inc/header.php' ?>
	<?php include '../inc/navbar.php' ?>
	<?php handle_messages_html(); ?>
	<!-- Begin Orders Main Body -->
	<?php
		if (user_is_logged_in())
			include_once $_SERVER['DOCUMENT_ROOT'] . '/../inc/orders_panel.php';
		else
			echo "<p>You must be logged in to view your orders.</p><br>";
	?>
	<!-- end Orders Main Body -->
	<?php include '../inc/footer.php' ?>
</body>
</html>

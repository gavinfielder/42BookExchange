<!DOCTYPE html>
<?php include_once '../src/master.php' ?>
<html>
<body>
	<div class="box">
	<h1>Orders for your listings</h1>
	<div class="flex_container">
	<?php
		$orders = get_order_data(logged_on_user());
		if ($orders != null)
		{
			foreach ($orders as $order)
			{
				echo "<div class=\"individual\">";
				echo order_div($order);
				echo "</div>";
			}
		}
		else
			echo "<p>You have no orders to fill.</p>";
	?>
	</div>
	<h1>Orders you have placed</h1>
	<div class="flex_container">
	<?php
		$orders = get_orders_by_user(logged_on_user());
		if ($orders != null)
		{
			foreach ($orders as $order)
				echo order_div($order);
		}
		else
			echo "<p>You are not waiting on any books.</p>";
	?>
	</div>
	</div>
</body>
</html>

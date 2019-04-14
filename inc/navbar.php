<?php
session_start();

include_once('../src/master.php');

function make_login_link()
{
	if (isset($_SESSION['logged_on_user']) && $_SESSION['logged_on_user'] != "")
		echo "<a href=\"logout.php\">Logout</a>";
	else
		echo "<a href=\"login.php\">Login</a> <a href=\"register.php\">Register</a>";
}

function make_login_who_text()
{
	echo "Logged in as: " . get_logged_on_user();
}

function make_admin_panel_link()
{
	if (isset($_SESSION['logged_on_user'])
		&& $_SESSION['logged_on_user'] != ""
		&& is_admin($_SESSION['logged_on_user']))
		echo "<a href=\"admin.php\">Administrator Panel</a>";
}

?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<div id="navbar">
		<button onclick="window.location.href = 'index.php';">Home</button>
		<button onclick="window.location.href = 'add_entry.php';">Add Entry</button>
		<button onclick="window.location.href = 'basket.php';">My Basket</button>
		<button onclick="window.location.href = 'orders.php';">My Orders</button>
		<button onclick="window.location.href = 'account.php';">My Account</button>
		<div class=login_control align="right">
			<?php make_login_who_text(); ?>
			<?php make_login_link(); ?>
			<?php make_admin_panel_link(); ?>
		</div>
	</div>
</body>
</html>

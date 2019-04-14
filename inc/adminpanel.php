<!DOCTYPE html>
<?php include_once '../src/master.php' ?>
<html>
<body>
	<h1>User Administration</h2>
	<h2>Users:</h2>
	<table id="admin_user_table">
		<tr><td><b>email</b></td><td><b>Administrator</b></td></tr>
		<?php
			$passwd = load_account_data();
			foreach ($passwd as $account)
			{
				echo "<tr><td>", $account['email'],
					"</td><td>", $account['is_admin'], "</td></tr>\n";
			}
		?>
	</table>
	<br>
	<h2>Remove User</h2>
	<form method="POST" action="admin.php?action=remove_user">
		<label>Intra Login:</label><br>
		<input type="text" name="user_to_remove"><br>
		<input type="submit" name="submit" value="OK"><br>
	</form>
	<h2>Make User Admin</h2>
	<form method="POST" action="admin.php?action=make_user_admin">
		<label>Intra Login:</label><br>
		<input type="text" name="user_to_make_admin"><br>
		<label>Set to value (1 or 0):</label><br>
		<input type="text" name="set_to"><br>
		<input type="submit" name="submit" value="OK"><br>
	</form>
</body>
</html>

<!DOCTYPE html>
<html>
<body>
<h1><?php echo logged_on_user(); ?>'s Account</h2>
	<h2>Change Password</h2>
	<form method="POST" action="account.php?action=change_password">
		<label>Old Password:</label><br>
		<input type="password" name="old_pw"><br>
		<label>New Password:</label><br>
		<input type="password" name="new_pw"><br>
		<input type="submit" name="submit" value="OK"><br>
	</form>
	<h2>Add a Custom Message to your Profile</h2>
	<form method="POST" action="account.php?action=set_custom_message">
		<textarea class=account_custom_textbox name=message></textarea><br>
		<input type="submit" name="submit" value="OK">
	</form>
	<h2>Your Listings</h2>
		<?php
		$my_books = array();
		$books = get_database();
		foreach ($books as $book)
		{
			if ($book['owner'] == logged_on_user())
				$my_books[] = $book;
		}
		$html = list_book_array($my_books);
		echo $html;
		?>
</body>
</html>

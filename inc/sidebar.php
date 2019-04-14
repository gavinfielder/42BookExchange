<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<div id="sidebar">
		<h2>Tags</h2>
		<?php 
			$all_tags = get_all_tags();
			echo "<a class=tagbutton href='index.php'>View All Books</a><br>";
			foreach ($all_tags as $tag)
			{
				echo "<a class=tagbutton href='index.php?tag=" . $tag . "'>";
				echo $tag . "</a><br>\n";
			}
		?>
	</div>
</body>
</html>

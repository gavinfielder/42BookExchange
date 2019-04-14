<html><body>
<form action="add_entry.php" method="GET">
	<div class="add_entry">
		<label>Name: <label/>
		<br>
		<input type="text" class="input_box" name="name" placeholder="Name"  />
		<br>
		<br>
		<label>Photo URL (optional):<label/>
		<br>
		<input type="text" class="input_box" name="url" placeholder="URL"  />
		<br>
		<br>
		<label>Quantity (1 or more):</label>
		<br>
		<input type="text" class="input_box" name="count" placeholder="Quantity"  />
		<br>
		<br>
		<label>Description (optional):<label/>
		<br>
		<input type="text" class="input_box" name="descr" placeholder="Description"  />
		<br>
		<br>
		<label>Author (optional):<label/>
		<br>
		<input type="text" class="input_box" name="auth" placeholder="Author"  />
		<br>
		<br>
		<label>Year Published (optional):<label/>
		<br>
		<input type="text" class="input_box" name="pub_year" placeholder="YYYY"  />
		<br>
		<br>
		<label>Tags (separate with comma ','):</label>
		<br>
		<input type="text" class="input_box" name="tags" />
		<br>
		<br>
		<label>Your Selling Price (USD):</label>
		<br>
		<input type="text" class="input_box" name="price" value="1.00"/>
		<br>
		<br>
		<input type="submit" name="submit" class=submit_button value="OK" />
	<div>
</form>
</body></html>

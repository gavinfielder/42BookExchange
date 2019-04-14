<?php

session_start();
setlocale(LC_ALL, "us");

function get_account($passwd, $email)
{
	foreach ($passwd as $account)
	{
		if ($account['email'] == $email)
			return $account;
	}
	return false;
}

function load_account_data()
{
	if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/../data)"))
		mkdir($_SERVER['DOCUMENT_ROOT'] . '/../data');
	if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/../data/accounts'))
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/../data/accounts', null);
	$passwd = unserialize(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../data/accounts'));
	return ($passwd);
}

function save_account_data($passwd)
{
	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/../data/accounts', serialize($passwd));
}

function auth($login, $pw)
{
	$passwd = load_account_data();
	$account = get_account($passwd, $login);
	if (!account)
		return false;
	if (hash('whirlpool', $pw) == $account['passwd'])
		return true;
	else
		return false;
}

function is_admin($email)
{
	$passwd = load_account_data();
	$account = get_account($passwd, $email);
	if (!($account))
		return false;
	if ($account['is_admin'] == 1)
		return true;
	return false;
}

function admin_authenticate()
{
	if (isset($_SESSION)
		&& isset($_SESSION['logged_on_user'])
		&& $_SESSION['logged_on_user'] != ""
		&& is_admin($_SESSION['logged_on_user']))
		return true;
	else
		return false;
}

function user_is_logged_in()
{
	if (isset($_SESSION)
		&& isset($_SESSION['logged_on_user'])
		&& $_SESSION['logged_on_user'] != "")
		return true;
	else
		return false;
}

function get_logged_on_user() {
	if (isset($_SESSION)
		&& isset($_SESSION['logged_on_user'])
		&& $_SESSION['logged_on_user'] != "")
		return $_SESSION['logged_on_user'] . "\n";
	else
		return "Not logged in";
}

function logged_on_user() {
	if (isset($_SESSION)
		&& isset($_SESSION['logged_on_user'])
		&& $_SESSION['logged_on_user'] != "")
		return $_SESSION['logged_on_user'];
	else
		return false;
}

function remove_user_error($msg)
{
	message_to_user("Error deleting user: $msg\n");
	redirect('admin.php');
	exit (0);
}

function remove_user($email)
{
	$passwd = load_account_data();
	foreach ($passwd as $key => $val)
	{
		if ($val['email'] == $email)
		{
			unset($passwd[$key]);
			save_account_data($passwd);
			remove_user_listings($email);
			remove_user_orders($email);
			message_to_user("User successfully deleted");
			redirect('../admin.php');
			return ;
		}
	}
	remove_user_error("User not found: " . $email);
}
function add_to_basket($book_id)
{
	if (isset($_SESSION['basket']) && in_array($book_id, $_SESSION['basket']))
	{
		message_to_user("That book is already in your basket");
		redirect('index.php');
	}
	$_SESSION['basket'][] = $book_id;
	header('Refresh:0');
}

function remove_from_basket($book_id)
{
	if (!isset($_SESSION['basket']))
		return ;
	$key = array_search($book_id, $_SESSION['basket']);
	if ($key === false)
		return ;
	unset($_SESSION['basket'][$key]);
	header('Refresh:0');
}

function get_database()
{
	$content = null;
	$path = $_SERVER['DOCUMENT_ROOT'] . "/../data/database";
	if (file_exists($path))
	{
		$content = file_get_contents($path);
		$content = unserialize($content);
	}
	return $content;
}

function make_database($content)
{
	$path = $_SERVER['DOCUMENT_ROOT'] . "/../data/database";
	file_put_contents($path,serialize($content));
}

function add_entry($info)
{
	$content = get_database();
	$content[$info['id']] = $info;
	make_database($content);
}

function remove_entry($id)
{
	$content = get_database();
	$content[$id]['count'] = 0;
	make_database($content);
}

function get_entry($id)
{
	$content = null;
	$content = get_database();
	return $content[$id];
}

function book_decrement_count($id)
{
	$books = get_database();
	$books[$id]['count']--;
	make_database($books);
}

function database_size()
{
	$content = get_database();
	return count($content);
}

function create_entry_error($msg)
{
	message_to_user("Error making listing: " . $msg);
	redirect('../add_entry.php');
}

function create_entry()
{

	if (!(isset($_GET)
		&& isset($_GET["name"])
		&& isset($_GET["descr"])
		&& isset($_GET["auth"])
		&& isset($_GET["pub_year"])
		&& isset($_GET["submit"])
		&& $_GET["submit"] == "OK"
		&& isset($_GET["count"])))
		create_entry_error("Improper request");
	if (!(ctype_digit($_GET["count"])
		&& $_GET["count"] > 0))
		create_entry_error("Quantity must be a positive integer");
	if (!(is_numeric($_GET['price']) && $_GET['price'] >= 0 ))
		create_entry_error("Your selling price must be a nonnegative number");
	if ($_GET['name'] == "")
		create_entry_error("Name cannot be blank.");
	$arr["name"] = $_GET["name"];
	if ($_GET['auth'] != "" && !ctype_alpha($_GET['auth']))
	{
		create_entry_error("author name cannot have non-alphabet characters");
	}
	if (!ctype_alnum($_GET['name']))
	{
		create_entry_error("book name cannot have non-alphabet characters");
	}
	if (array_key_exists("url",$_GET) && $_GET['url'] != "")
	{
		if (!filter_var($_GET["url"], FILTER_VALIDATE_URL))
			create_entry_error("Image url does not appear to be a valid url.");
		$arr["url"] = $_GET["url"];
	}
	else
		$arr["url"] = "../img/default.png";
	$arr["descr"] = $_GET["descr"];
	$arr["auth"] = $_GET["auth"];
	if (!(ctype_digit($_GET["pub_year"]) && strlen($_GET['pub_year']) == 4))
		$arr['pub_year'] = "";
	else
		$arr["pub_year"] = $_GET["pub_year"];
	$arr['price'] = $_GET['price'];
	$arr["timestamp"] = time();
	$arr['id'] = $arr['timestamp'];
	$arr["count"] = $_GET["count"];
	$tags = explode(',', $_GET["tags"]);
	foreach ($tags as $key => $val)
	{
		$arr["tags"][] = trim($val);
		if ($val != "" && !ctype_alnum(trim($val)))
			create_entry_error("tag needs to be alpha-numeric");
	}
	$arr["owner"] = $_SESSION["logged_on_user"];
	add_entry($arr);
	message_to_user("Book listed successfully!");
	redirect('../index.php');
}

function get_all_tags()
{
	$tags = array();
	$books_data = get_database();
	foreach ($books_data as $book)
	{
		foreach ($book["tags"] as $tag)
		{
			if ($book['count'] > 0 && (!in_array($tag, $tags)))
				$tags[] = $tag;
		}
	}
	return $tags;
}

function get_all_with_tag($tag)
{
	$books_data = get_database();
	$result = array();
	foreach ($books_data as $book)
	{
		if ($book['count'] > 0 && in_array($tag, $book["tags"]))
			$result[] = $book;
	}
	return $result;
}

function book_listing_div($book)
{
	$html = "<div class=listing>";
	$html .= "<img src='" . $book['url'] . "' class=thumb alt='image'>";
	$html .= "<span class=name_label></span><span class=name>" . $book['name'] . "</span><br>";
	$html .= "<span class=author_label>Author:</span><span class=author> " . $book['auth'] . "</span><br>";
	$html .= "<span class=description_label>Description:</span> ";
	$html .= "<div class=description>\"" . $book['descr'] . "\"</div>";
	$html .= "<span class=pub_year>Published: " . $book['pub_year'] . "</span><br>";
	$html .= "<span class=owner_label>Owned by: </span><span class=owner><a  href=\"user.php?id=".$book["owner"]."\" >" . $book['owner'] . "</a></span>";
	if ($book['count'] > 1)
		$html .= "<span class=num_copies> (" . $book['count'] . " copies)</span><br>";
	else
		$html .= "<br>";
	$html .= "<span class=price_label>Price: </span><span class=price>" . money_format('$%i', $book['price']) . "</span><br>";
	$html .= "<span class=date>Listed on " . date("F j, Y, g:i a", $book['timestamp']) . "</span><br>";
	$html .= "<span class=tags_label>Tags: </span><span class=tags>" . implode(", ", $book['tags']) . "</span><br>";
	$html .= "<div class=control>";
	if (isset($_SESSION['basket']) && in_array($book['id'], $_SESSION['basket']))
		$html .= "<span class=in_basket_text>In Basket</span>";
	elseif (logged_on_user() == $book['owner'])
		$html .= "<span class=owned_listing>Your Listing</span>";
	else
		$html .= "<span class=add_to_basket_link><a href='index.php?action=add-to-basket&id=" . $book['id'] . "'>Add to Basket</a></span>";
	if (user_is_logged_in() && ($_SESSION['logged_on_user'] == $book['owner'] || admin_authenticate()))
		$html .= " | <span class=remove_listing_link><a href='index.php?action=remove-listing&id=" . $book['id'] . "'>Remove Listing</a></span>";
	$html .= "</div></div>\n";
	return $html;
}

function book_inbasket_div($book)
{
	$html = "<div class=basket_item>";
	$html .= "<label>Name: " . $book['name'] . "</label><br>";
	$html .= "<label>Owned by: " . $book['owner'] . "</label><br>";
	$html .= "<label>Price: " . money_format('$%i', $book['price']) . "</label><br>";
	$html .= "<a href='basket.php?action=remove&id=" . $book['id'] . "'>Remove from Basket</a>";
	$html .= "</div>\n";
	return $html;
}

function list_book_array($books)
{
	$html = "";
	foreach ($books as $id => $book)
		if ($book['count'] > 0)
			$html .= book_listing_div($book);
	return $html;
}

function list_basket()
{
	$html = "";
	if (isset($_SESSION['basket']))
	{
		foreach ($_SESSION['basket'] as $id)
		{
			$book = get_entry($id);
			$html .= book_inbasket_div($book, $id);
		}
	}
	return $html;
}

function get_basket_total()
{
	$total = 0;
	if (isset($_SESSION['basket']))
	{
		foreach ($_SESSION['basket'] as $id)
		{
			$book = get_entry($id);
			$total += $book['price'];
		}
	}
	return $total;
}


function login_error($msg)
{
	message_to_user("Error logging in: $msg\n");
	redirect('../login.php');
	exit (0);
}

function login()
{
	if (!(isset($_POST)
		&& isset($_POST['email'])
		&& isset($_POST['passwd'])
		&& isset($_POST['submit'])
		&& $_POST['submit'] === "OK"))
		login_error("Improper request.");

	$login = $_POST['email'];
	$pw = $_POST['passwd'];
	if (auth($login, $pw))
	{
		$_SESSION['logged_on_user'] = $login;
		unset($pw);
		validate_basket();
		message_to_user("Successfully logged in!");
		redirect('../index.php');
	}
	else
	{
		$_SESSION['logged_on_user'] = "";
		login_error("Invalid username/password");
		unset($pw);
	}
}

function flush_basket()
{
	unset($_SESSION['basket']);
}

function validate_basket()
{
	$user = logged_on_user();
	$again = false;
	if (isset($_SESSION['basket']) && count($_SESSION['basket']) > 0 && $user != false)
	{
		foreach ($_SESSION['basket'] as $book_id)
		{
			$book = get_entry($book_id);
			if ($book['owner'] == $user || (!($book['count'] > 0)))
			{
				remove_from_basket($book_id);
				$again = true;
				break ;
			}
		}
	}
	if ($again)
		validate_basket();
}

function logout()
{
	if (isset($_SESSION) && isset($_SESSION['logged_on_user']))
	{
		$_SESSION['logged_on_user'] = "";
		flush_basket();
		message_to_user("Successfully logged out!");
	}
	redirect('../index.php');
}

function make_admin_error($msg)
{
	message_to_user("Error making user admin: $msg\n");
	redirect('../admin.php');
	exit (0);
}

function make_user_admin($email, $admin_set)
{
	$passwd = load_account_data();
	foreach ($passwd as $key => $val)
	{
		if ($val['email'] == $email)
		{
			if (!($admin_set == '0' ||  $admin_set == '1'))
				make_admin_error("Invalid value: " . $admin_set);
			$passwd[$key]['is_admin'] = $admin_set;
			save_account_data($passwd);
			message_to_user($email . "'s admin status has been set to " . $admin_set);
			redirect('../admin.php');
			return ;
		}
	}
	make_admin_error("User not found: " . $email);
}

function message_to_user($msg)
{
	$_SESSION['message'] = $msg;
}

function handle_messages()
{
	if (isset($_SESSION['message']))
	{
		echo $_SESSION['message'] . "\n";
		unset($_SESSION['message']);
	}
}

function handle_messages_html()
{
	if (isset($_SESSION['message']))
	{
		echo "<p>" . $_SESSION['message'] . "</p><br>\n";
		unset($_SESSION['message']);
	}
}

function redirect($page)
{
	ob_start();
	header('Location: ' . $page);
	ob_end_flush();
	exit (0);
}

function new_account_error($msg)
{
	message_to_user("Error setting up new account: " . $msg);
	redirect('register.php');
	exit(0);
}

function new_account()
{
	if (!(isset($_POST)
		&& isset($_POST['email'])
		&& isset($_POST['passwd'])
		&& $_POST['passwd'] != ""
		&& isset($_POST['submit'])
		&& $_POST['submit'] === "OK"))
	{
		new_account_error("Improper request.");
	}
	if (!ctype_alnum($_POST['email']))
	{
		new_account_error("login needs to be alpha-numeric");
	}

	$passwd = load_account_data();
	$account = get_account($passwd, $_POST['email']);
	if ($account)
		new_account_error("Account already exists: " . $account['email']);
	unset($account);
	$account['email'] = $_POST['email'];
	$account['passwd'] = hash('whirlpool', $_POST['passwd']);
	if ($passwd == null || count($passwd) < 1)
		$account['is_admin'] = 1;
	else
		$account['is_admin'] = 0;
	$account['num_sold'] = 0;
	$account['num_bought'] = 0;
	$account['custom'] = "";
	$passwd[$account['email']] = $account;
	save_account_data($passwd);
	message_to_user("New account created: " . $account['email']);
	redirect('login.php');
}

function change_password($email, $oldpw, $newpw)
{
	$passwd = load_account_data();
	$account = get_account($passwd, $email);
	if (!$account)
		return ;
	if ($account['passwd'] == hash('whirlpool', $oldpw))
	{
		$passwd[$email]['passwd'] = hash('whirlpool', $newpw);
		save_account_data($passwd);
		message_to_user("Password successfully changed.");
		$newpw = "";
		$oldpw = "";
		redirect('account.php');
	}
	else
	{
		message_to_user("Incorrect password.");
		$newpw = "";
		$oldpw = "";
		redirect('account.php');
	}
}

function get_order($email, $order_id)
{
	$orders = get_order_data($email);
	foreach ($orders as $id => $order)
	{
		if ($id == $order_id)
			return $order;
	}
	return null;
}


function get_order_data($email)
{
	$content = null;
	$path = $_SERVER['DOCUMENT_ROOT'] . "/../data/orders/" . $email;
	if (file_exists($path))
		$content = unserialize(file_get_contents($path));
	return $content;
}

function save_order_data($email, $orders)
{
	if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/../data/orders"))
		mkdir($_SERVER['DOCUMENT_ROOT'] . "/../data/orders");
	file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/../data/orders/" . $email,
		serialize($orders));
}

function add_order($email, $order)
{
	$content = get_order_data($email);
	$content[$order['timestamp']] = $order;
	save_order_data($email, $content);
}

function new_order($book_id)
{
	//Precondition: user is logged in
	$order['requester'] = $_SESSION['logged_on_user'];
	$order['book_id'] = $book_id;
	$order['timestamp'] = time();
	$books = get_database();
	$book = $books[$book_id];
	if ($book['count'] > 0)
	{
		add_order($book['owner'], $order);
		book_decrement_count($book_id);
		return 1;
	}
	else
		return 0;
}

function remove_order($email, $order_id,$filled)
{
	$orders = get_order_data($email);
	if ($orders != null && array_key_exists($order_id, $orders))
	{
		if ($filled)
		{
			$password = load_account_data();
			$account = get_account($password,$email);
			if (!$account)
				return ;
			$password[$account['email']]['num_sold']++;
			$account = get_account($password, $orders[$order_id]['requester']);
			if (!$account)
				return ;
			$password[$account['email']]['num_bought']++;
			save_account_data($password);
		}
		unset($orders[$order_id]);
		save_order_data($email, $orders);
	}
}

function get_orders_by_user($login)
{
	$results = array();
	$passwd = load_account_data();
	foreach ($passwd as $email => $account)
	{
		$orders = get_order_data($email);
		if ($orders != null)
		{
			foreach ($orders as $order)
			{
				if ($order['requester'] == $login)
					$results[] = $order;
			}
		}
	}
	return $results;
}

function order_div($order)
{
	$book_id = $order['book_id'];
	$book = get_entry($book_id);
	$html = "<div class=order_listing>";
	$html .= "<div class=\"individual\">";
	$html .= "<label>Name: " . $book['name'] . "</label><br>";
	$html .= "<label>Owned by: " . $book['owner'] . "</label><br>";
	$html .= "<label>Requested by: " . $order['requester'] . "</label><br>";
	$html .= "<label>Price: " . money_format('$%i', $book['price']) . "</label><br>";
	$html .= "<label>Listed on: " . date("F j, Y, g:i a", $book['timestamp']) . "</label><br>";
	$html .= "<label>Requested on: " . date("F j, Y, g:i a", $order['timestamp']) . "</label><br>";
	if (logged_on_user() == $book['owner'])
		$html .= "<a href='orders.php?action=remove&order_id=" . $order['timestamp'] . "&user=" . $book['owner'] . "'>Mark as Filled</a><br>";
	if (logged_on_user() == $book['owner'])
		$html .= "<a href='orders.php?action=cancel&order_id=" . $order['timestamp'] . "&user=" . $book['owner'] . "'>cancel</a><br>";
	$html .="</div>";
	$html .= "</div>\n";
	return $html;
}

function remove_user_listings($login)
{
	$user_book_ids = array();
	$books = get_database();
	foreach ($books as $book_id => $book)
	{
		if ($book['owner'] == $login)
			$user_book_ids[] = $book_id;
	}
	foreach ($user_book_ids as $id)
	{
		remove_entry($id);
	}
}

function remove_user_orders($login)
{
	$orders = get_order_data($login);
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/..data/orders/" . $login))
		unlink($_SERVER['DOCUMENT_ROOT'] . "/..data/orders/" . $login);
	
	$passwd = load_account_data();
	foreach ($passwd as $email => $account)
	{
		$orders = get_order_data($email);
		if ($orders != null)
		{
			foreach ($orders as $order)
			{
				if ($order['requester'] == $login)
					remove_order($email, $order['timestamp']);
			}
		}
	}
}

function set_custom_message($login, $message)
{
	$passwd = load_account_data();
	$account = get_account($passwd, $login);
	if (!$account)
		return ;
	$passwd[$account['email']]['custom'] = $message;
	save_account_data($passwd);
	message_to_user("Custom message has been set!");
	redirect('account.php');
}

?>

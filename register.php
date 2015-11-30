<?php 
include 'core/init.php';
logged_in_redirect();
include 'includes/overall/overall_header.php'; 

if (empty($_POST) === false){
	$required_fields = array('username', 'password', 'password_again', 'first_name', 'last_name', 'email');
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true) {
			$errors[] = 'Fields marked with an asterisk are required';
			break 1;
		}

	}

	if (empty($errors) === true){
		if (user_exists($_POST['username']) === true) {
			$errors[] = 'Sorry, that username \'' . htmlentities($_POST['username']) . '\' is already taken.';
		}
		if (preg_match("/\\s/", $_POST['username']) == true) {
			$errors[] = 'Sorry, your username must not contain any spaces';
		}
		if (strlen($_POST['password']) < 6) {
			$errors[] = 'Your password must have more than 6 characters.';
		}
		if ($_POST['password'] !== $_POST['password_again']) {
			$errors[] = 'Your passwords do not match';
		}
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
			$errors[] = 'A valid email address is required';
		}
		if (email_exists($_POST['email']) === true) {
			$errors[] = 'Sorry, that email \'' . htmlentities($_POST['email']) . '\' is already in use.';
		}

	}
}


?>

<h1>Register</h1>

<?php
if (isset($_GET['success']) && empty($_GET['success']) && $_POST) {
	echo 'You\'ve been sucessfully registered!';
} else {
	if (empty($_POST) === false && empty($errors) === true) {
		$register_data = array(
			'username' 		=> $_POST['username'],
			'password' 		=> $_POST['password'],
			'first_name' 	=> $_POST['first_name'],
			'last_name' 	=> $_POST['last_name'],
			'email' 		=> $_POST['email']
			);

		register_user($register_data);
		header('Location: register.php?success');
		exit();
		
	} else if (empty($errors) === false){
		echo output_errors($errors);
	}
}
?>

<form action="" method="post">
	<ul>
		<li>
			Username*:<br>
			<input class="form-control" type="text" name="username">
		</li>
		<li>
			Password*:<br>
			<input class="form-control" type="password" name="password">
		</li>
		<li>
			Password again*:<br>
			<input class="form-control" type="password" name="password_again">
		</li>
		<li>
			First Name*:<br>
			<input class="form-control" type="text" name="first_name">
		</li>
		<li>
			Last Name*:<br>
			<input class="form-control" type="text" name="last_name">
		</li>
		<li>
			Email*:<br>
			<input class="form-control" type="text" name="email">
		</li>
		<li>
			<input class="btn btn-primary" type="submit" value="Register">
		</li>
	</ul>
</form>

<?php include 'includes/overall/overall_footer.php'; ?>

<!-- code for log in to function -->
<?php
	session_start();

	// connect to database im_project
	include 'dbconnect.php';
	
	// check if the form is submitted
	if(isset($_POST['submit'])) {
		// get the form inputs
		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);

		// query the users table to check if the username and password match
		$check_user = "SELECT * FROM users WHERE username='$username' AND password='$password'";
		$result = mysqli_query($conn, $check_user);

		if(mysqli_num_rows($result) == 1) {
			// user exists, set session variables and redirect to appropriate page
			$user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['uid'];
            header('Location: accountpage.php');
			exit;
		
		} else {
			// user does not exist or password is incorrect, display error message
			echo 'Invalid username or password. Please try again.';
		}
	}
    

	// close the database connection
	mysqli_close($conn);
?>


<!-- display of log in form -->
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
	<h2>Login</h2>
	<form method="post" action="">
		<label>Username:</label>
		<input type="text" name="username"><br><br>

		<label>Password:</label>
		<input type="password" name="password"><br><br>

		<input type="submit" name="submit" value="Login">
	</form>

</body>
</html>

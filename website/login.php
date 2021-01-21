<?php
/**
 * @file login.php
 *
 * @brief On this page the admin can login to the website
 */

// Starting a session if one has not yet been established.
if (!isset($_SESSION)) {
    session_start();
}
// Since there is only 1 user the password is hardcoded into the website.
$correct_username = "tester";
$correct_password = "leenfiets123";
?>
<!DOCTYPE html>
<html>
	<head>

	<head>
	<body>
		<form method="post" id="LoginForm">
		    <input name="username" id="username" placeholder="Username"> <br />
		    <input type="password" name="password" id="password" placeholder="Password"> <br />
		    <input type="submit" name="login" value="Login">
		</form>
	</body>
</html>
<?php
// Checking if the login form has been submitted.
if(isset($_POST['login'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	// Checking if all the fields have been filled in.
	$fields = array('username', 'password');
	$missing_field = false;
	foreach ($fields as $fieldname) {
        if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
            echo $fieldname . " has not been filled in.<br />";
            $error = true;
        }
    }
	// Checking the username and passwords are correct
	if ($correct_username == $username && $correct_password == $password) {
		// If the login is succesfull we start a session
		$_SESSION["logged_in"] = true;
		header('Location: index.php');
	} else {
		echo "Username or password is incorrect.";
	}
}
?>
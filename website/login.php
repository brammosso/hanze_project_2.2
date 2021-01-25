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
        <meta charset="utf-8">
        <title>Osaka Univsersity Login</title>
        <link rel="stylesheet" type="text/css" href="styles_login.css">
	<head>
	<body>
        <div class="login_div">
            <div class="logo"></div>
            <div class="title">Osaka University</div>
            <div class="sub-title">Dashboard</div>
            <form method="post">
                <div class="input-form">
		            <input name="username" id="username" placeholder="Username"> <br />
		            <input type="password" name="password" id="password" placeholder="Password"> <br />
                </div>
                    <input class="signin-button" type="submit" name="login" value="Login">
		    </form>
        </div>
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
                    $error = true;
                }
            }
            // Checking the username and passwords are correct
            if ($correct_username == $username && $correct_password == $password) {
                // If the login is succesfull we start a session
                $_SESSION["logged_in"] = true;
                header('Location: index.php');
            } else {
                echo "<div class='error'>fout</div>";
            }
        }
        ?>
	</body>
</html>


<?php

declare(strict_types = 1);

require_once(__DIR__ . '/config/config.php');
require_once(__DIR__ . '/includes/form_handlers/register_handler.php');
require_once(__DIR__ . '/includes/form_handlers/login_handler.php');
?>

<html>
<head>
    <title>Welcome to the society!</title>  
    <link rel="stylesheet" type="text/css" href="/assets/css/register_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="/assets/js/register.js"></script>
</head>
<body>
    <div class="wrapper">
        <div class="login-box">
            <div class="login-header">
                <h1>Social!</h1>
                Login or sign up below
            </div>
            <div id="first">

                <form action="register.php" method="post">
                    <input type="email" name="log_email" placeholder="Email Address" value="<?php htmlspecialchars($_SESSION['reg_fname'] ?? ''); ?>" required>
                    <br />
                    <input type="password" name="log_password" placeholder="Password">
                    <br />
                    <input type="submit" name="login_button" value="Login">
                    <br />
                    <a href="#" id="signup" class="signup">Need an account? Register here!</a>
                </form>
                <?php
                    if (in_array("Incorrect password or email", $error_array)) echo "Incorrect password or email";
                ?>
            </div>
            <div id="second">
                
                <form action="register.php" method="post">
                    <input type="text" name="reg_fname" placeholder="First Name" value="<?= htmlspecialchars($_SESSION['reg_fname'] ?? '')?>" required />
                    <?php
                        if (in_array("Your first name must be between 2 and 25 characters<br />", $error_array)) echo "Your first name must be between 2 and 25 characters<br />";
                    ?>
                    <br />
                    <input type="text" name="reg_lname" placeholder="Last Name" value="<?= htmlspecialchars($_SESSION['reg_lname'] ?? '')?>" required />
                    <?php
                        if (in_array("Your last name must be between 2 and 25 characters<br />", $error_array)) echo "Your last name must be between 2 and 25 characters<br />";
                    ?>
                    <br />
                    <input type="email" name="reg_email" placeholder="Email" value="<?= htmlspecialchars($_SESSION['reg_email'] ?? '')?>" required />
                    <br />
                    <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?= htmlspecialchars($_SESSION['reg_email2'] ?? '')?>" required />
                    <?php
                        if (in_array("Email is already in use<br />", $error_array)) echo "Email is already in use<br />";
                        if (in_array("Invalid email format<br />", $error_array)) echo "Invalid email format<br />";
                        if (in_array("Emails don't match<br />", $error_array)) echo "Emails don't match<br />";
                    ?>
                    <br />
                    <input type="password" name="reg_password" placeholder="Password" required />
                    <br />
                    <input type="password" name="reg_password2" placeholder="Confirm Password" required />
                    <?php
                        if (in_array("Your password do not match<br />", $error_array)) echo "Your password do not match<br />";
                        if (in_array("Your password can only contain english letters or numbers<br />", $error_array)) echo "Your password can only contain english letters or numbers<br />";
                        if (in_array("Your password must be between 5 and 30 characters<br />", $error_array)) echo "Your password must be between 5 and 30 characters<br />";
                    ?>
                    <br />
                    <input type="submit" name="register_button" value="Register" />
                    <br />
                    <a href="#" id="signin" class="signin">Already have an account? Sign in here!</a>
                
                    <?php
                        if (in_array("Mysql error: check your database", $error_array)) echo "Mysql error: check your database";
                        if (in_array("<span style=\"color: green;\">You're all set! Goahead and login!</span><br />", $error_array)) echo "<span style=\"color: green;\">You're all set! Goahead and login!</span><br />";
                    ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php

declare(strict_types = 1);

require_once(__DIR__ . '/config/config.php');
require_once(__DIR__ . '/includes/form_handlers/register_handler.php');
?>

<html>
<head>
    <title>Welcome to the society!</title>
</head>
<body>
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

        <?php
            if (in_array("Mysql error: check your database", $error_array)) echo "Mysql error: check your database";
            if (in_array("<span style=\"color: green;\">You're all set! Goahead and login!</span><br />", $error_array)) echo "<span style=\"color: green;\">You're all set! Goahead and login!</span><br />";
        ?>
    </form>
</body>
</html>

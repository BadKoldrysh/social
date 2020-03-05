<?php

declare(strict_types = 1);

session_start();

$con = new mysqli("localhost", "root", "", "social");
if ($err = $con->connect_errno) {
    printf("Connection failed: %s", $err);
}

// Declaring variables to prevent errors
$fname = ""; // First name
$lname = ""; // Last name
$email = ""; // Email
$email2 = ""; // Email 2
$password = ""; // Password
$password2 = ""; // Password 2
$date = "";
$error_array = [];

if (isset($_POST['register_button'])) {
    // Registration form values
    // First name
    $fname = strip_tags($_POST['reg_fname'] ?? ''); // remove html tags
    $fname = str_replace(' ', '', $fname); // remove spaces
    $fname = ucfirst(strtolower($fname)); // uppercase first letter
    $_SESSION['reg_fname'] = $fname; // stores first name into session variable

    // Last name
    $lname = strip_tags($_POST['reg_lname'] ?? ''); // remove html tags
    $lname = str_replace(' ', '', $lname); // remove spaces
    $lname = ucfirst(strtolower($lname)); // uppercase first letter
    $_SESSION['reg_lname'] = $lname; // stores last name into session variable
    
    // Email
    $email = strip_tags($_POST['reg_email'] ?? ''); // remove html tags
    $email = str_replace(' ', '', $email); // remove spaces
    $email = strtolower($email); // uppercase first letter
    $_SESSION['reg_email'] = $email; // stores last name into session variable
    
    // Email 2
    $email2 = strip_tags($_POST['reg_email2'] ?? ''); // remove html tags
    $email2 = str_replace(' ', '', $email2); // remove spaces
    $email2 = strtolower($email2); // uppercase first letter
    $_SESSION['reg_email2'] = $email2; // stores last name into session variable

    // Password
    $password = strip_tags($_POST['reg_password'] ?? ''); // remove html tags
    $password = str_replace(' ', '', $password); // remove spaces

    // Password 2
    $password2 = strip_tags($_POST['reg_password2'] ?? ''); // remove html tags
    $password2 = str_replace(' ', '', $password2); // remove spaces

    $date = date("Y-m-d");

    if ($email === $email2) {
        // Check if email is in valid format
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);

            // Check if email already exists
            $e_check = mysqli_query($con, "SELECT email FROM users WHERE email = '$email'");

            // Count the number of rows returned
            $num_rows = mysqli_num_rows($e_check);

            if ($num_rows > 0) {
                array_push($error_array, "Email is already in use<br />");
            }
        } else {
            array_push($error_array, "Invalid email format<br />");
        }
    } else {
        array_push($error_array, "Emails don't match<br />");
    }

    if (strlen($fname) > 25 || strlen($fname) < 2) {
        array_push($error_array, "Your first name must be between 2 and 25 characters<br />");
    }
    if (strlen($lname) > 25 || strlen($lname) < 2) {
        array_push($error_array, "Your last name must be between 2 and 25 characters<br />");
    }

    if ($password !== $password2) {
        array_push($error_array, "Your password do not match<br />");
    } else {
        if (preg_match("/[^A-Za-z0-9]/", $password)) {
            array_push($error_array, "Your password can only contain english letters or numbers<br />");
        }
    }

    if (strlen($password) > 30 || strlen($password) < 5) {
        array_push($error_array, "Your password must be between 5 and 30 characters<br />");
    }

    if (empty($error_array)) {
        $password = md5($password); // encrypt password before send to database

        // Generate username by concatenating first name and last name
        $username = strtolower($fname . '_' . $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");

        $i = 0;
        // if username exists add number to username
        while($check_username_query != false &&
                mysqli_num_rows($check_username_query) != 0) {
            $i++;
            $username = $username . "_" . $i;

            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");
        }

        $rand = rand(1, 2);

        if ($rand === 1) {
            $profile_pic = "assets/images/profile_pics/default/head_deep_blue.png";
        } else {
            $profile_pic = "assets/images/profile_pics/default/head_emerald.png";
        }

        $query = mysqli_query($con, "INSERT INTO users VALUES('', '$fname', '$lname', '$username', '$email', '$password', '$date', '$profile_pic', '0', '0', 'no', '.')");
        
        array_push($error_array, "<span style=\"color: green;\">You're all set! Goahead and login!</span><br />");
        session_unset();
    }
}
var_dump($_SESSION);

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
            if (in_array("<span style=\"color: green;\">You're all set! Goahead and login!</span><br />", $error_array)) echo "<span style=\"color: green;\">You're all set! Goahead and login!</span><br />";
        ?>
    </form>
</body>
</html>
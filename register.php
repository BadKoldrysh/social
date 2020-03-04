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
$error_array = "";

if (isset($_POST['register_button'])) {
    // Registration form values
    // First name
    $fname = strip_tags($_POST['reg_fname']); // remove html tags
    $fname = str_replace(' ', '', $fname); // remove spaces
    $fname = ucfirst(strtolower($fname)); // uppercase first letter
    $_SESSION['reg_fname'] = $fname; // stores first name into session variable

    // Last name
    $lname = strip_tags($_POST['reg_lname']); // remove html tags
    $lname = str_replace(' ', '', $lname); // remove spaces
    $lname = ucfirst(strtolower($lname)); // uppercase first letter
    $_SESSION['reg_lname'] = $lname; // stores last name into session variable
    
    // Email
    $email = strip_tags($_POST['reg_email']); // remove html tags
    $email = str_replace(' ', '', $email); // remove spaces
    $email = strtolower($email); // uppercase first letter
    $_SESSION['reg_email'] = $email; // stores last name into session variable
    
    // Email 2
    $email2 = strip_tags($_POST['reg_email2']); // remove html tags
    $email2 = str_replace(' ', '', $email2); // remove spaces
    $email2 = strtolower($email2); // uppercase first letter
    $_SESSION['reg_email2'] = $email2; // stores last name into session variable

    // Password
    $password = strip_tags($_POST['reg_email']); // remove html tags
    $password = str_replace(' ', '', $password); // remove spaces

    // Password 2
    $password2 = strip_tags($_POST['reg_email2']); // remove html tags
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
                echo "Email already in use";
            }
        } else {
            echo "Invalid format";
        }
    } else {
        echo "Emails don't match";
    }

    if (strlen($fname) > 25 || strlen($fname) < 2) {
        echo "Your first name must be between 2 and 25 characters";
    }
    if (strlen($lname) > 25 || strlen($lname) < 2) {
        echo "Your last name must be between 2 and 25 characters";
    }

    if ($password !== $password2) {
        echo "Your password do not match";
    } else {
        if (preg_match("/[^A-Za-z0-9]/", $password)) {
            echo "Your password can only contain english letters or numbers";
        }
    }

    if (strlen($password) > 30 || strlen($password) < 5) {
        echo "Your password must be between 5 and 30 characters";
    }
}

?>

<html>
<head>
    <title>Welcome to the society!</title>
</head>
<body>
    <form action="register.php" method="post">
        <input type="text" name="reg_fname" placeholder="First Name" value="<?= $_SESSION['reg_fname'] ?? ''?>" required />
        <br />
        <input type="text" name="reg_lname" placeholder="Last Name" value="<?= $_SESSION['reg_lname'] ?? ''?>" required />
        <br />
        <input type="email" name="reg_email" placeholder="Email" value="<?= $_SESSION['reg_email'] ?? ''?>" required />
        <br />
        <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?= $_SESSION['reg_email2'] ?? ''?>" required />
        <br />
        <input type="password" name="reg_password" placeholder="Password" required />
        <br />
        <input type="password" name="reg_password" placeholder="Confirm Password" required />
        <br />
        <input type="submit" name="register_button" value="Register" />
    </form>
</body>
</html>
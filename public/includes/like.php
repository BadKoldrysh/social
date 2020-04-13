<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/classes/User.php');
require_once(__DIR__ . '/classes/Post.php');
require_once(__DIR__ . '/classes/Helper.php');
?>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="/../assets/css/style.css">
</head>
<body>
    <?php

    if (isset($_SESSION['username'])) {
        $userLoggedIn = $_SESSION['username']; 
        $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
        $user = mysqli_fetch_array($user_details_query);
    } else {
        header("Location: register.php");
    }

    ?>
    
</body>
</html>
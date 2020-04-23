<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../config/config.php');

if (isset($_SESSION['username'])) {
    $userLoggedIn = $_SESSION['username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
    $user = mysqli_fetch_array($user_details_query);
} else {
    header("Location: register.php");
}
?>

<html>
<head>
    <!-- META -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="/../assets/js/bootstrap.js"></script>
    <script src="/../assets/js/bootbox.min.js"></script>
    <script src="/../assets/js/demo.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/../assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>A Social Network Web</title>
</head>
<body>
    <div class="top_bar">
        <div class="logo">
            <a href="index.php">Social Network</a>
        </div>
        <nav>
            <a href="<?= $userLoggedIn?>">
                <?php
                    echo $user['first_name'];
                ?>
            </a>
            <a href="index.php">
                <i class="fa fa-home fa-lg"></i>
            </a>
            <a href="#">
                <i class="fa fa-envelope fa-lg"></i>
            </a>
            <a href="#">
                <i class="fa fa-bell-o fa-lg"></i>
            </a>
            <a href="#">
                <i class="fa fa-users fa-lg"></i>
            </a>
            <a href="#">
                <i class="fa fa-cog fa-lg"></i>
            </a>
            <a href="includes/handlers/logout.php">
                <i class="fa fa-sign-out fa-lg"></i>
            </a>
        </nav>
    </div>

    <div class="wrapper">


<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../config/config.php');

if (isset($_SESSION['username'])) {
    $userLoggedIn = $_SESSION['username']; 
} else {
    // header("Location: register.php");
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
    
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/../assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/../assets/css/style.css">
    
    <title>A Social Network Web</title>
</head>
<body>
    <div class="top_bar">
        <div class="logo">
            <a href="index.php">Social Network</a>
        </div>
    </div>
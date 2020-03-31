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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="/../assets/js/bootstrap.js"></script>
    <link rel="stylesheet" type="text/css" href="/../assets/css/bootstrap.css">
    <title>A Social Network Web</title>
</head>
<body>
<br />
<button type="button" class="btn btn-primary">Hello</button>
<br />
<div class="dropdown">
  <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Dropdown button
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="#">Action</a>
    <a class="dropdown-item" href="#">Another action</a>
    <a class="dropdown-item" href="#">Something else here</a>
  </div>
</div>
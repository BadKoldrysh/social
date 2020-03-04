<?php

declare(strict_types = 1);

$con = new mysqli("localhost", "root", "", "social");
if ($err = $con->connect_errno) {
    printf("Connection failed: %s", $err);
}

?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Social Network Web</title>
</head>
<body>
    <h1>Hello there</h1>
</body>
</html>

<?php

declare(strict_types = 1);

$con = new mysqli("localhost", "root", "", "test_db");
if ($err = $con->connect_errno) {
    printf("Connection failed: %s", $err);
}

$query = mysqli_query($con, "INSERT INTO test VALUES(NULL, 'Mr Robot')");
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

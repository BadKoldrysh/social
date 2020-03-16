<?php

declare(strict_types = 1);

ob_start(); // turns on output buffering

session_start();

$timezone = date_default_timezone_set("Europe/Prague");

$host = 'mysql';
$username = 'root';
$password = 'social';
$dbName = 'social';

$con = new mysqli($host, $username, $password, $dbName);
if ($err = $con->connect_errno) {
    printf("Connection failed: %s", $err);
}

<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "event_management";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

echo "Database connected successfully!";

?>
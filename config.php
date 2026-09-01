<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "event_&_ticket_management";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>
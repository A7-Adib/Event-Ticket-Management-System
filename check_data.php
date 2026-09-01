<?php

require_once "C:/xampp/htdocs/Event-Ticket-Management-System/config/database.php";

echo "Connected database: " . $conn->query("SELECT DATABASE()")->fetch_row()[0] . PHP_EOL;

$result = $conn->query("SELECT COUNT(*) AS total FROM events");
$row = $result->fetch_assoc();
echo "Events in database: " . $row["total"] . PHP_EOL;

$result = $conn->query("SELECT COUNT(*) AS total FROM users");
$row = $result->fetch_assoc();
echo "Users in database: " . $row["total"] . PHP_EOL;

$result = $conn->query("SELECT COUNT(*) AS total FROM registrations");
$row = $result->fetch_assoc();
echo "Registrations in database: " . $row["total"] . PHP_EOL;

echo PHP_EOL . "Events:" . PHP_EOL;

$result = $conn->query("SELECT event_id, event_name FROM events");

while ($row = $result->fetch_assoc()) {
    echo "#" . $row["event_id"] . " - " . $row["event_name"] . PHP_EOL;
}

?>
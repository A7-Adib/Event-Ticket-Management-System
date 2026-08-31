<?php
require_once '../config/database.php';

if (!isset($_GET['id'])) {
    die("Event ID not found.");
}

$event_id = $_GET['id'];

$sql = "DELETE FROM events WHERE event_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);

if ($stmt->execute()) {
    header("Location: view_events.php");
    exit();
} else {
    echo "Error deleting event.";
}

$stmt->close();
$conn->close();
?>
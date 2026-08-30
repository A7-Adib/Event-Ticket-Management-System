<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "event_management";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die(" Database Connection Failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Query to fetch names from tickets table
$sql = "SELECT name FROM tickets";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='8' cellspacing='0'>";
    echo "<tr><th>Ticket Holder Name</th></tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row["name"]) . "</td></tr>";
    }
    
    echo "</table>";
} else {
    echo "No names found in tickets table.";
}

$conn->close();
?>

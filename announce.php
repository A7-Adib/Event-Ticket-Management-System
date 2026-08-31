<?php
include "db.php";

$sql = "SELECT * FROM announcements
        ORDER BY created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Announcements</title>

    <style>

        body {
            font-family: Arial;
            margin: 30px;
        }

        .announcement {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        }

    </style>

</head>

<body>

<h2>Announcements</h2>

<?php

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        echo "<div class='announcement'>";

        echo "<h3>" .
             htmlspecialchars($row["title"]) .
             "</h3>";

        echo "<p>" .
             htmlspecialchars($row["message"]) .
             "</p>";

        echo "<small>" .
             $row["created_at"] .
             "</small>";

        echo "</div>";
    }

} else {

    echo "<p>No announcements available.</p>";

}

?>

<br>

<a href="index.php">Back to Home</a>

</body>

</html>


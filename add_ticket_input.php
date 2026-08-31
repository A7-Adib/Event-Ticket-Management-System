<?php
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $attendee_name = trim($_POST["attendee_name"]);
    $event_name = trim($_POST["event_name"]);
    if ($attendee_name == "" || $event_name == "") {

        $message = "Please fill in all fields.";

    } else {
        $ticket_code = "TKT-" . strtoupper(substr(md5(uniqid()), 0, 8));

        $sql = "INSERT INTO tickets
                (ticket_code, attendee_name, event_name)
                VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sss",
            $ticket_code,
            $attendee_name,
            $event_name
        );

        if ($stmt->execute()) {

            $message = "Ticket Generated Successfully! Ticket Code: " . $ticket_code;

        } else {

            $message = "Error generating ticket.";

        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Generate Ticket</title>
</head>

<body>

<h2>Generate Ticket</h2>

<?php
if ($message != "") {
    echo "<p><b>$message</b></p>";
}
?>

<form method="POST">

    <label>Attendee Name:</label>
    <br>

    <input type="text" name="attendee_name" required>

    <br><br>

    <label>Event Name:</label>
    <br>

    <input type="text" name="event_name" required>

    <br><br>

    <button type="submit">
        Generate Ticket
    </button>

</form>

<br>

<a href="index.php">Back to Home</a>

</body>

</html>
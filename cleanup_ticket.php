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

        if ($stmt) {

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

        } else {

            $message = "Database query preparation failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Generate Ticket</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f5f5f5;
        }

        h2 {
            margin-bottom: 20px;
        }

        form {
            background-color: white;
            padding: 20px;
            width: 400px;
            border-radius: 8px;
        }

        label {
            font-weight: bold;
        }

        input {
            padding: 8px;
            width: 350px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 9px 20px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }

        a {
            text-decoration: none;
        }

    </style>

</head>

<body>

<h2>Generate Ticket</h2>

<?php

if ($message != "") {
    echo "<p><b>" . htmlspecialchars($message) . "</b></p>";
}
?>

<form method="POST">

    <label for="attendee_name">Attendee Name:</label>
    <br>

    <input
        type="text"
        id="attendee_name"
        name="attendee_name"
        maxlength="100"
        required
    >

    <br><br>

    <label for="event_name">Event Name:</label>
    <br>

    <input
        type="text"
        id="event_name"
        name="event_name"
        maxlength="100"
        required
    >

    <br><br>

    <button type="submit">
        Generate Ticket
    </button>

</form>

<br>

<a href="index.php">Back to Home</a>

</body>

</html>
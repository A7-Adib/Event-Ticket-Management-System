<?php
include "db.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $announcement = trim($_POST["message"]);
    if ($title == "" || $announcement == "") {

        $message = "Please fill in all fields.";

    } else {
        $sql = "INSERT INTO announcements
                (title, message)
                VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param(
                "ss",
                $title,
                $announcement
            );
            if ($stmt->execute()) {

                $message = "Announcement Published Successfully!";

            } else {

                $message = "Failed to publish announcement.";
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
    <title>Add Announcement</title>
</head>

<body>

<h2>Add Announcement</h2>

<?php

if ($message != "") {
    echo "<p><b>$message</b></p>";
}
?>

<form method="POST">

    <label>Title:</label>
    <br>

    <input
        type="text"
        name="title"
        required
    >

    <br><br>

    <label>Announcement:</label>
    <br>

    <textarea
        name="message"
        rows="5"
        cols="40"
        required
    ></textarea>

    <br><br>

    <button type="submit">
        Publish
    </button>

</form>

<br>

<a href="index.php">Back to Home</a>

</body>

</html>
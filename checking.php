<?php
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $staff_name = $_POST["staff_name"];
    $ticket_code = $_POST["ticket_code"];

    $sql = "INSERT INTO check_in
            (staff_name, ticket_code)
            VALUES (?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ss",
        $staff_name,
        $ticket_code
    );

    if ($stmt->execute()) {

        $message = "Check-in Successful!";

    } else {

        $message = " Check-in Failed!";

    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Staff Check-in</title>
</head>

<body>

<h2> Event Staff Check-in</h2>

<?php

if ($message != "") {
    echo "<p><b>$message</b></p>";
}

?>

<form method="POST">

    <label>Staff Name:</label>
    <br>

    <input
        type="text"
        name="staff_name"
        required
    >

    <br><br>

    <label>Ticket Code:</label>
    <br>

    <input
        type="text"
        name="ticket_code"
        required
    >

    <br><br>

    <button type="submit">
        Check-in
    </button>

</form>

<br>

<a href="index.php">Back to Home</a>

</body>

</html>
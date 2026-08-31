<?php
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $staff_name = trim($_POST["staff_name"]);
    $ticket_code = trim($_POST["ticket_code"]);
    if ($staff_name == "" || $ticket_code == "") {

        $message = "Please fill in all fields.";

    } else {

        $sql = "INSERT INTO check_in
                (staff_name, ticket_code)
                VALUES (?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt) {

            $stmt->bind_param(
                "ss",
                $staff_name,
                $ticket_code
            );

            if ($stmt->execute()) {

                $message = "Check-in Successful!";

            } else {

                $message = "Check-in Failed!";

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

    <title>Staff Check-in</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f5f5f5;
        }

        form {
            background-color: white;
            padding: 20px;
            width: 400px;
            border-radius: 8px;
        }

        input {
            padding: 8px;
            width: 350px;
            margin-top: 5px;
        }

        button {
            padding: 8px 18px;
            cursor: pointer;
        }

        a {
            text-decoration: none;
        }
    </style>

</head>

<body>

<h2>Event Staff Check-in</h2>

<?php
if ($message != "") {
    echo "<p><b>" . htmlspecialchars($message) . "</b></p>";
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
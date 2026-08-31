<?php
include "config.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["attendee_name"]);
    $event = trim($_POST["event_name"]);
    $type = trim($_POST["ticket_type"]);

    if ($name == "" || $event == "") {

        $message = "Please fill in all required fields.";
        $message_type = "error";

    } else {

        do {
            $ticket_code = "TKT" . strtoupper(substr(md5(uniqid()), 0, 8));

            $check = $conn->prepare(
                "SELECT id FROM tickets WHERE ticket_code=?"
            );

            $check->bind_param("s", $ticket_code);
            $check->execute();

            $result = $check->get_result();

        } while ($result->num_rows > 0);

        $check->close();

        $sql = "INSERT INTO tickets
                (ticket_code, attendee_name, event_name, ticket_type)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssss",
            $ticket_code,
            $name,
            $event,
            $type
        );

        if ($stmt->execute()) {

            $message =
                "Ticket generated successfully! Ticket Code: "
                . $ticket_code;

            $message_type = "success";

        } else {

            $message = "Failed to generate ticket.";
            $message_type = "error";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Generate Ticket</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="navbar">

<h2>Event Management System</h2>

<div>
<a href="index.php">Dashboard</a>
<a href="ticket_generate.php">Generate</a>
<a href="ticket_verify.php">Verify</a>
<a href="checkin.php">Check-in</a>
<a href="announcements.php">Announcements</a>
</div>

</div>

<div class="container">

<div class="form-box">

<h2>Generate Ticket</h2>

<?php if ($message != ""): ?>

<div class="<?php echo $message_type; ?>">
    <?php echo htmlspecialchars($message); ?>
</div>

<?php endif; ?>

<form method="POST">

<label>Attendee Name</label>

<input
type="text"
name="attendee_name"
required
>

<label>Event Name</label>

<input
type="text"
name="event_name"
required
>

<label>Ticket Type</label>

<select name="ticket_type">

<option value="Regular">Regular</option>
<option value="VIP">VIP</option>
<option value="Student">Student</option>

</select>

<button class="btn" type="submit">
Generate Ticket
</button>

</form>

</div>

</div>

</body>
</html>
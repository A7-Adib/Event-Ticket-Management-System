<?php
include "config.php";

$ticket = null;
$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $code = trim($_POST["ticket_code"]);

    $sql = "SELECT * FROM tickets WHERE ticket_code=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $code);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $ticket = $result->fetch_assoc();

        if ($ticket["status"] == "Valid") {

            $message = "Valid ticket. This ticket is ready for check-in.";
            $message_type = "success";

        } elseif ($ticket["status"] == "Used") {

            $message = "This ticket has already been used.";
            $message_type = "warning";

        } else {

            $message = "This ticket has been cancelled.";
            $message_type = "error";
        }

    } else {

        $message = "Invalid Ticket Code.";
        $message_type = "error";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Verify Ticket</title>

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

<h2>Ticket Verification</h2>

<?php if ($message != ""): ?>

<div class="<?php echo $message_type; ?>">
<?php echo htmlspecialchars($message); ?>
</div>

<?php endif; ?>

<form method="POST">

<label>Ticket Code</label>

<input
type="text"
name="ticket_code"
placeholder="Example: TKT12AB34CD"
required
>

<button class="btn" type="submit">
Verify Ticket
</button>

</form>

<?php if ($ticket): ?>

<div class="ticket">

<h3>Ticket Information</h3>

<p>
<strong>Ticket Code:</strong>
<?php echo htmlspecialchars($ticket["ticket_code"]); ?>
</p>

<p>
<strong>Attendee:</strong>
<?php echo htmlspecialchars($ticket["attendee_name"]); ?>
</p>

<p>
<strong>Event:</strong>
<?php echo htmlspecialchars($ticket["event_name"]); ?>
</p>

<p>
<strong>Type:</strong>
<?php echo htmlspecialchars($ticket["ticket_type"]); ?>
</p>

<p>
<strong>Status:</strong>

<span class="status
<?php
if ($ticket["status"] == "Valid") {
    echo " valid";
} elseif ($ticket["status"] == "Used") {
    echo " used";
} else {
    echo " cancelled";
}
?>
">

<?php echo htmlspecialchars($ticket["status"]); ?>

</span>

</p>

<?php if ($ticket["status"] == "Valid"): ?>

<a
class="btn btn-success"
href="checkin.php?ticket=<?php echo urlencode($ticket["ticket_code"]); ?>"
>
Proceed to Check-in
</a>

<?php endif; ?>

</div>

<?php endif; ?>

</div>

</div>

</body>
</html>
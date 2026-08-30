<?php
include "config.php";

$message = "";
$message_type = "";

$ticket_code = "";

if (isset($_GET["ticket"])) {
    $ticket_code = $_GET["ticket"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ticket_code = trim($_POST["ticket_code"]);

    /*
       First check whether ticket exists
    */

    $sql = "SELECT * FROM tickets WHERE ticket_code=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ticket_code);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {

        $message = "Invalid Ticket Code.";
        $message_type = "error";

    } else {

        $ticket = $result->fetch_assoc();

        /*
           Check current ticket status
        */

        if ($ticket["status"] == "Cancelled") {

            $message = "This ticket has been cancelled.";
            $message_type = "error";

        } elseif ($ticket["status"] == "Used") {

            /*
               Check whether check-in record exists
            */

            $check = $conn->prepare(
                "SELECT * FROM check_in WHERE ticket_code=?"
            );

            $check->bind_param("s", $ticket_code);
            $check->execute();

            $check_result = $check->get_result();

            if ($check_result->num_rows > 0) {

                $checkin = $check_result->fetch_assoc();

                $message =
                    "Duplicate Check-in Prevented! This ticket was already checked in on "
                    . $checkin["checkin_time"];

                $message_type = "warning";

            } else {

                $message =
                    "This ticket has already been used.";

                $message_type = "warning";
            }

            $check->close();

        } else {

            /*
               Ticket is VALID
            */

            /*
               Check duplicate before inserting
            */

            $check = $conn->prepare(
                "SELECT id FROM check_in WHERE ticket_code=?"
            );

            $check->bind_param("s", $ticket_code);
            $check->execute();

            $check_result = $check->get_result();

            if ($check_result->num_rows > 0) {

                $message = "This ticket has already been checked in.";
                $message_type = "warning";

            } else {

                /*
                   Insert check-in record
                */

                $insert = $conn->prepare(
                    "INSERT INTO check_in
                    (ticket_code, attendee_name, status)
                    VALUES (?, ?, 'Checked-In')"
                );

                $insert->bind_param(
                    "ss",
                    $ticket["ticket_code"],
                    $ticket["attendee_name"]
                );

                if ($insert->execute()) {

                    /*
                       Change ticket status
                       Valid -> Used
                    */

                    $update = $conn->prepare(
                        "UPDATE tickets
                         SET status='Used'
                         WHERE ticket_code=? AND status='Valid'"
                    );

                    $update->bind_param("s", $ticket_code);
                    $update->execute();

                    $update->close();

                    $message =
                        "Check-in Successful! Welcome "
                        . $ticket["attendee_name"] . ".";

                    $message_type = "success";

                } else {

                    if ($conn->errno == 1062) {

                        $message =
                            "Duplicate Check-in Prevented!";

                        $message_type = "warning";

                    } else {

                        $message =
                            "Check-in failed. Please try again.";

                        $message_type = "error";
                    }
                }

                $insert->close();
            }

            $check->close();
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Event Staff Check-in</title>

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

<h2>Event Staff Check-in</h2>

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
value="<?php echo htmlspecialchars($ticket_code); ?>"
placeholder="Enter Ticket Code"
required
>

<button class="btn btn-success" type="submit">
Check-in
</button>

</form>

</div>

</div>

</body>
</html>
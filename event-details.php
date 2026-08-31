<?php

include "database.php";

$id = $_GET['id'];

$sql = "SELECT * FROM events WHERE event_id = $id";

$result = mysqli_query($conn, $sql);

$event = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html>
<head>

    <title>Event Details</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="container">

    <h2>
    <?php echo $event['event_name']; ?>
</h2>

<p>
    <?php echo $event['description']; ?>
</p>

<p>
    <b>Date:</b>
    <?php echo $event['date']; ?>
</p>

<p>
    <b>Time:</b>
    <?php echo $event['time']; ?>
</p>

<p>
    <b>Location:</b>
    <?php echo $event['location']; ?>
</p>

<p>
    <b>Capacity:</b>
    <?php echo $event['capacity']; ?>
</p>

<p>
    <b>Status:</b>
    <?php echo $event['status']; ?>
</p>

        <a href="events.php">
            <h3>Back to Events</h3>
        </a>

    </div>

</div>

</body>
</html>
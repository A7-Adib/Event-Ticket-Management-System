<?php
include "config.php";

$tickets = $conn->query("SELECT COUNT(*) AS total FROM tickets")->fetch_assoc()["total"];

$valid = $conn->query(
    "SELECT COUNT(*) AS total FROM tickets WHERE status='Valid'"
)->fetch_assoc()["total"];

$used = $conn->query(
    "SELECT COUNT(*) AS total FROM tickets WHERE status='Used'"
)->fetch_assoc()["total"];

$checkins = $conn->query(
    "SELECT COUNT(*) AS total FROM check_in WHERE status='Checked-In'"
)->fetch_assoc()["total"];

$announcements = $conn->query(
    "SELECT COUNT(*) AS total FROM announcements"
)->fetch_assoc()["total"];
?>

<!DOCTYPE html>
<html>
<head>

<title>Event Management Dashboard</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="navbar">

    <h2>Event Management System</h2>

    <div>
        <a href="index.php">Dashboard</a>
        <a href="ticket_generate.php">Generate Ticket</a>
        <a href="ticket_verify.php">Verify Ticket</a>
        <a href="checkin.php">Check-in</a>
        <a href="announcements.php">Announcements</a>
    </div>

</div>

<div class="container">

    <h1 class="dashboard-title">
        Dashboard
    </h1>

    <div class="cards">

        <div class="card">
            <h3>Total Tickets</h3>
            <div class="card-number">
                <?php echo $tickets; ?>
            </div>
        </div>

        <div class="card">
            <h3>Valid Tickets</h3>
            <div class="card-number">
                <?php echo $valid; ?>
            </div>
        </div>

        <div class="card">
            <h3>Used Tickets</h3>
            <div class="card-number">
                <?php echo $used; ?>
            </div>
        </div>

        <div class="card">
            <h3>Total Check-ins</h3>
            <div class="card-number">
                <?php echo $checkins; ?>
            </div>
        </div>

        <div class="card">
            <h3>Announcements</h3>
            <div class="card-number">
                <?php echo $announcements; ?>
            </div>
        </div>

    </div>

</div>

</body>
</html>
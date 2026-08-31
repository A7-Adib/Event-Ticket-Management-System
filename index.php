<?php
session_start();

include("config/database.php");

if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Welcome - Event and Ticket Management</title>

    <link rel="stylesheet" href="CSS/style.css">
</head>

<body>

    <!-- Homepage Image -->
     <div class="hero-image">
     <img src="image/event-banner.jpg" alt="Event Image" width="1500">
     </div>

    <h1>Welcome to Event and Ticket Management System</h1>

    <p>Please login or register to continue.</p>

    <br>

    <a href="login.php">
        <button type="button">Login</button>
    </a>

    <a href="register.php">
        <button type="button">Register</button>
    </a>

</body>

</html>
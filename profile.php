<?php

session_start();

include "config/database.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();

}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = '$user_id'";

$result = mysqli_query($conn, $sql);

if (!$result) {

    die("Database Error: " . mysqli_error($conn));

}

$user = mysqli_fetch_assoc($result);

if (!$user) {

    die("User not found!");

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>My Profile</title>

    <link rel="stylesheet" href="CSS/style.css">

</head>

<body>

<div class="event-details">
    <h2>My Profile</h2>

    <p>
        <strong>User ID:</strong>
        <?php echo $user['id']; ?>
    </p>

    <p>
        <strong>Name:</strong>
        <?php echo htmlspecialchars($user['name']); ?>
    </p>

    <p>
        <strong>Email:</strong>
        <?php echo htmlspecialchars($user['email']); ?>
    </p>

    <p>
        <strong>Role:</strong>
        <?php echo htmlspecialchars($user['role']); ?>
    </p>

    <br>

    <a href="update_profile.php">
        Update Profile
    </a>

    <br><br>

    <a href="logout.php">
        Logout
    </a>

</div>

</body>

</html>


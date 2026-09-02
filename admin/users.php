<?php
session_start();
include "config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>

<h2>Welcome, <?php echo $user['name']; ?></h2>

<p><strong>Name:</strong> <?php echo $user['name']; ?></p>
<p><strong>Email:</strong> <?php echo $user['email']; ?></p>
<p><strong>Role:</strong> <?php echo $user['role']; ?></p>

<br>
<a href="update_profile.php">Edit Profile</a> | 
<a href="logout.php">Logout</a>

</body>
</html>
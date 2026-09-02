<?php
session_start();
include "config/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id='$user_id'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
}

if (isset($_POST['update'])) {
    $user_id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET name='$name', email='$email', role='$role' WHERE id='$user_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>

<h2>Edit User</h2>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

    <label>Name:</label><br>
    <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br><br>

    <label>Role:</label><br>
    <select name="role">
        <option value="user" <?php if($user['role'] == 'user') echo 'selected'; ?>>User</option>
        <option value="admin" <?php if($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
    </select><br><br>

    <button type="submit" name="update">Update</button>
</form>

<br>
<a href="admin_dashboard.php">Back to Dashboard</a>

</body>
</html>
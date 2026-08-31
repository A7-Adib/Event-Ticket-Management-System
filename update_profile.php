
<?php

session_start();

include "config/database.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();

}

$user_id = $_SESSION['user_id'];
if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // If password is entered, update password too
    if (!empty($password)) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE users
                SET name='$name',
                    email='$email',
                    password='$hashed_password'
                WHERE id='$user_id'";

    } else {

        // Update only name and email
        $sql = "UPDATE users
                SET name='$name',
                    email='$email'
                WHERE id='$user_id'";
    }

    if (mysqli_query($conn, $sql)) {

        $_SESSION['user_name'] = $name;

        echo "Profile updated successfully!";

    } else {

        echo "Error updating profile: " . mysqli_error($conn);

    }
}

// Get current user information

$sql = "SELECT * FROM users WHERE id='$user_id'";

$result = mysqli_query($conn, $sql);

$user = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Update Profile</title>

    <link rel="stylesheet" href="CSS/style.css">

</head>

<body>

<h2>Update Profile</h2>

<form method="POST">

    <label>Name:</label><br>

    <input
        type="text"
        name="name"
        value="<?php echo htmlspecialchars($user['name']); ?>"
        required
    >

    <br><br>

    <label>Email:</label><br>

    <input
        type="email"
        name="email"
        value="<?php echo htmlspecialchars($user['email']); ?>"
        required
    >

    <br><br>

    <label>New Password:</label><br>

    <input
        type="password"
        name="password"
        placeholder="Leave blank to keep current password"
    >

    <br><br>

    <button type="submit" name="update">
        Update Profile
    </button>

</form>

<br>

<a href="profile.php">
    Back to Profile
</a>

</body>

</html>

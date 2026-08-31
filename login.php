<?php

session_start();

include "config/database.php";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn, $sql);

    if (!$result) {

        die("Database Error: " . mysqli_error($conn));

    }

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];

            header("Location: profile.php");
            exit();

        } else {

            echo "Wrong password!";

        }

    } else {

        echo "User not found!";

    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Login</title>
    <link rel="stylesheet" href="CSS/style.css">

</head>

<body>

<h2>Login</h2>

<form method="POST">

    Email:
    <input type="email" name="email" required>

    <br><br>

    Password:
    <input type="password" name="password" required>

    <br><br>

    <button type="submit" name="login">Login</button>

</form>

<br>

<a href="index.php">
    <button type="button">Back to Homepage</button>
</a>

</body>

</html>
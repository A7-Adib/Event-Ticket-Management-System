<?php

include "config/database.php";

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {

        echo "Passwords do not match!";

    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password)
                VALUES ('$name', '$email', '$hashed_password')";

        if (mysqli_query($conn, $sql)) {

            echo "Registration successful!";

        } else {

            echo "Error: " . mysqli_error($conn);

        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>

<body>

<h2>User Registration</h2>

<form method="POST">

    <label>Name:</label><br>
    <input type="text" name="name" required>
    <br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required>
    <br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required>
    <br><br>

    <label>Confirm Password:</label><br>
    <input type="password" name="confirm_password" required>
    <br><br>

    <button type="submit" name="register">Register</button>

</form>

</body>
</html>
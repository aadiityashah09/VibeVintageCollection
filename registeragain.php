<?php
session_start();
include('db_connection.php');  // Make sure this file contains your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);  // Plain-text password
    $role = 'customer';  // Default role, adjust if necessary

    // Check if the username already exists
    $check_query = "SELECT * FROM users WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "Username already exists. Please choose a different username.";
    } else {
        // Get the email
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        // Correct the variable name for the insert query
        $insert_query = "INSERT INTO users (username, password, email, role) VALUES ('$username', '$password', '$email', '$role')";

        // Insert the new user into the database
        if (mysqli_query($conn, $insert_query)) {
            echo "Registration successful!";
            // Optionally, redirect to the login page after successful registration
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - VibeVintage Collection</title>
</head>
<body>
    <h1>Register</h1>
    <form action="register.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>

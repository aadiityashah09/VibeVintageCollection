<?php
session_start();
include('db_connection.php');  // Ensure this file contains your database connection logic

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input with basic sanitization
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = trim(mysqli_real_escape_string($conn, $_POST['password']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $role = 'customer';  // Default role for new users

    // Input validation
    if (empty($username) || empty($password) || empty($email)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Check if the username already exists
        $check_query = "SELECT * FROM users WHERE username = '$username'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "Username already exists. Please choose a different username.";
        } else {
            // Store the plain-text password (not secure)
            $insert_query = "INSERT INTO users (username, password, email, role) VALUES ('$username', '$password', '$email', '$role')";
            if (mysqli_query($conn, $insert_query)) {
                // Redirect to login with a success status
                header("Location: login.php?status=registered");
                exit();
            } else {
                $error = "Error during registration: " . mysqli_error($conn);
            }
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
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <div class="register-container">
        <h1>Register</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Register" class="btn">
            </div>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>

<?php
session_start(); // Start the session

// Include the database connection
include('db_connection.php'); // Ensure db_connection.php contains a valid $conn variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Validate the inputs
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Query to check the username and password (plain text)
        $query = "SELECT id, password, role FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Compare plain text password
            if ($password === $user['password']) {
                // Successful login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                
                // Redirect based on role
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - VibeVintage Collection</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Login</button>
            </div>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>

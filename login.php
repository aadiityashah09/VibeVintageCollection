<?php
session_start();
include('db_connection.php'); // Ensure db_connection.php contains your database connection logic

$success = ""; // Variable to store success messages

if (isset($_GET['status']) && $_GET['status'] === 'loggedin') {
    $success = "You have successfully logged in.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Validate the inputs
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Query to check the username and password
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
                
                // Redirect with a status parameter to show the success popup
                header("Location: login.php?status=loggedin");
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
    <style>
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            border: 2px solid #ccc;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .popup.active {
            display: block;
        }
        .popup-close {
            cursor: pointer;
            color: #f00;
            font-weight: bold;
            margin-left: 10px;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .overlay.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="successPopup">
        <p><?php echo $success; ?></p>
        <button class="popup-close" onclick="closePopup()">Close</button>
    </div>

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

    <script>
        // Show popup if there's a success message
        window.onload = function() {
            const successMessage = "<?php echo $success; ?>";
            if (successMessage) {
                document.getElementById('successPopup').classList.add('active');
                document.getElementById('overlay').classList.add('active');

                // Redirect to index.php after 3 seconds
                setTimeout(() => {
                    window.location.href = "index.php";
                }, 3000);
            }
        };

        // Close popup function
        function closePopup() {
            document.getElementById('successPopup').classList.remove('active');
            document.getElementById('overlay').classList.remove('active');
            // Immediate redirect to index.php if the popup is closed
            window.location.href = "index.php";
        }
    </script>
</body>
</html>

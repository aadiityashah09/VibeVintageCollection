<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session and check admin login
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vibevintage";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch counts for dashboard summary
$categories = [
    'men_collection' => 'Men\'s Collection',
    'women_collection' => 'Women\'s Collection',
    'kids_collection' => 'Kids\' Collection',
];

$counts = [];
foreach ($categories as $table => $label) {
    $result = $conn->query("SELECT COUNT(*) as total FROM $table");
    $row = $result->fetch_assoc();
    $counts[$table] = $row['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - VibeVintage</title>
    <link rel="stylesheet" href="admincss.css">
    <style>
        /* Inline styles for basic layout */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 1rem 0;
            text-align: center;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .nav {
            display: flex;
            justify-content: space-around;
            margin-bottom: 2rem;
        }
        .nav a {
            text-decoration: none;
            background-color: #333;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }
        .nav a:hover {
            background-color: #555;
        }
        .summary {
            display: flex;
            justify-content: space-around;
            margin-top: 1rem;
        }
        .summary-item {
            background: #eaeaea;
            padding: 1rem;
            border-radius: 5px;
            text-align: center;
            width: 30%;
        }
        .summary-item h3 {
            margin: 0;
        }
        footer {
            text-align: center;
            margin-top: 2rem;
            padding: 1rem 0;
            background-color: #333;
            color: #fff;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Panel - VibeVintage Collection</h1>
    </header>
    <div class="container">
        <nav class="nav">
            
            <a href="admin_men.php">Manage Men's Collection</a>
            <a href="admin_women.php">Manage Women's Collection</a>
            <a href="admin_kids.php">Manage Kids' Collection</a>
            <a href="invoices.php">View Customer Invoices</a> <!-- Added link to invoices.php -->
            <a href="logout.php">Logout</a>
        </nav>
        
        <section class="dashboard">
            <h2>Welcome, Admin!</h2>
            <div class="summary">
                <!-- Men's Collection -->
                <div class="summary-item">
                    <h3>Men's Collection</h3>
                    <p>Total Products: <?= $counts['men_collection'] ?></p>
                    <a href="admin_men.php" class="btn">View Men's Products</a>
                </div>
                <!-- Women's Collection -->
                <div class="summary-item">
                    <h3>Women's Collection</h3>
                    <p>Total Products: <?= $counts['women_collection'] ?></p>
                    <a href="admin_women.php" class="btn">View Women's Products</a>
                </div>
                <!-- Kids' Collection -->
                <div class="summary-item">
                    <h3>Kids' Collection</h3>
                    <p>Total Products: <?= $counts['kids_collection'] ?></p>
                    <a href="admin_kids.php" class="btn">View Kids' Products</a>
                </div>
            </div>
        </section>
    </div>
    <footer>
        <p>&copy; 2024 VibeVintage Collection. All Rights Reserved.</p>
    </footer>
</body>
</html>

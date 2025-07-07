<?php
session_start();

// Include header and database connection
include('includes/header.php');
include('db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user details
$user_id = $_SESSION['user_id'];
$user_query = "SELECT id, username, email, role, created_at FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    die("User not found.");
}

// Get order history
$order_query = "
    SELECT id, order_date, total_price, shipping_fee, total_amount, payment_method 
    FROM orders 
    WHERE user_id = ?
    ORDER BY order_date DESC";
$order_stmt = $conn->prepare($order_query);
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <div class="account-container">
        <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? 'No email provided'); ?></p>
        <p><strong>Member Since:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
        <a href="logout.php" class="btn">Logout</a>
    </div>

    <div class="orders-container">
        <h2>Your Order History</h2>
        <?php if ($order_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total Price</th>
                        <th>Shipping Fee</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $order_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['id']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                            <td>₹<?php echo htmlspecialchars(number_format($order['total_price'], 2)); ?></td>
                            <td>₹<?php echo htmlspecialchars(number_format($order['shipping_fee'], 2)); ?></td>
                            <td>₹<?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></td>
                            <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have not placed any orders yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>

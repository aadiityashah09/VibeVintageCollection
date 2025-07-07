<?php
session_start();

// Check if user is logged in and has a valid role
if (!isset($_SESSION['user_id'])) {
    die("Access denied. You are not logged in.");
}

if (!isset($_SESSION['user_role'])) {
    die("Access denied. Role is not defined.");
}

if ($_SESSION['user_role'] != 'admin') {
    die("Access denied. You must be an admin to view this page.");
}

// Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vibevintage";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch orders with customer details
$query = " 
    SELECT o.id AS order_id, u.username AS customer_name, u.email AS customer_email, 
           o.total_price, o.shipping_fee, o.total_amount, o.payment_method, o.order_date 
    FROM orders o 
    INNER JOIN users u ON o.user_id = u.id 
    ORDER BY o.order_date DESC
";

$result = $conn->query($query);

if ($conn->error) {
    die("Error fetching orders: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Invoices</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>

<h1>Customer Invoices</h1>

<?php
if ($result->num_rows > 0) {
    // Display each order
    echo "<table border='1'>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Total Price</th>
                <th>Shipping Fee</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>Order Date</th>
            </tr>";

    while ($order = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $order['order_id'] . "</td>
                <td>" . $order['customer_name'] . "</td>
                <td>" . $order['customer_email'] . "</td>
                <td>₹" . $order['total_price'] . "</td>
                <td>₹" . $order['shipping_fee'] . "</td>
                <td>₹" . $order['total_amount'] . "</td>
                <td>" . $order['payment_method'] . "</td>
                <td>" . $order['order_date'] . "</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No orders found.</p>";
}
?>

<?php
// Close the connection
$conn->close();
?>

</body>
</html>

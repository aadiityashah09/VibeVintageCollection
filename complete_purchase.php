<?php
session_start();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the posted data
    $total_price = $_POST['total_price'];
    $shipping_fee = $_POST['shipping_fee'];
    $total_amount = $_POST['total_amount'];
    $payment_method = $_POST['payment_method']; // Either 'COD' or 'Online Payment'

    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("You must be logged in to place an order.");
    }
    $user_id = $_SESSION['user_id'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "vibevintage";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert order details into the database
    $sql = "INSERT INTO orders (user_id, total_price, shipping_fee, total_amount, payment_method, order_date) 
            VALUES ('$user_id', '$total_price', '$shipping_fee', '$total_amount', '$payment_method', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Order Confirmation</title>
            <link rel="stylesheet" href="styles.css">
        </head>
        <body>
            <h1>Order Confirmed!</h1>
            <p>Total Price: ₹$total_price</p>
            <p>Shipping Fee: ₹$shipping_fee</p>
            <p>Total Amount: ₹$total_amount</p>
            <p>Payment Method: $payment_method</p>
            <a href="index.php" class="btn btn-primary">Go Back to Homepage</a>
        </body>
        </html>
        HTML;

        // Optionally, clear the cart after completing the purchase
        unset($_SESSION['cart']);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
} else {
    echo "<p>Invalid request.</p>";
}
?>

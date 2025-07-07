<?php
session_start();

// Initialize total price
$total_price = 0;

// Check if the cart is not empty and calculate total price with BOGO offer
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        // For BOGO offer, charge for only one product per 2 items in the cart
        $quantity_to_charge = ceil($item['quantity'] / 2); // Only charge for 1 product per 2
        $total_price += $item['price'] * $quantity_to_charge;
    }

    // Calculate shipping fee: ₹50 if total price < ₹1000, else ₹0 (free shipping)
    $shipping_fee = ($total_price >= 1000) ? 0 : 50;
} else {
    // If no items in the cart, set shipping fee to 0
    $shipping_fee = 0;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - VibeVintage Collection</title>
    <link rel="stylesheet" href="styles2.css">

</head>
<body>
    <h1>Checkout</h1>

    <!-- If the cart is empty, show a message. Otherwise, display prices -->
    <?php if ($total_price > 0): ?>
        <p>Total Price: ₹<?= $total_price ?></p>
        <p>Shipping Fee: ₹<?= $shipping_fee ?></p>
        <p>Total Amount: ₹<?= $total_price + $shipping_fee ?></p>

        <!-- Payment Option: Cash on Delivery -->
        <form action="complete_purchase.php" method="POST">
            <input type="hidden" name="total_price" value="<?= $total_price ?>">
            <input type="hidden" name="shipping_fee" value="<?= $shipping_fee ?>">
            <input type="hidden" name="total_amount" value="<?= $total_price + $shipping_fee ?>">

            <!-- COD Option -->
            <label for="payment_method">Payment Method:</label>
            <select name="payment_method" id="payment_method">
                <option value="COD">Cash on Delivery</option>
                
            </select>

            <button type="submit">Complete Purchase</button>
        </form>
    <?php else: ?>
        <p align="center"> Your cart is empty. Please add some items to proceed with the checkout.</p>
    <?php endif; ?>

</body>
</html>

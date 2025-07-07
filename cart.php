<?php
session_start();

// Calculate the total price
$total_price = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_items = $_SESSION['cart'];
} else {
    $cart_items = [];
    echo "<p style='text-align: center;'>Your cart is empty</p>";

}



// Handle product deletion from cart
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Remove the product from the cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array after deletion
            break;
        }
    }

    // Redirect back to the cart page to see the updated cart
    header('Location: cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - VibeVintage</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <header>
        <h1>Your Cart</h1>
        <a href="checkout.php" class="btn">Proceed to Checkout</a>
    </header>
    <section class="cart">
        <div class="container">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>₹<?= htmlspecialchars($item['price']) ?></td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td>₹<?= htmlspecialchars($item['price'] * $item['quantity']) ?></td>
                            <td>
                                <!-- Delete button for each product -->
                                <a href="?action=delete&product_id=<?= $item['id'] ?>" class="btn-delete">Delete</a>
                            </td>
                        </tr>
                        <?php $total_price += $item['price'] * $item['quantity']; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p><strong>Total Price: ₹<?= $total_price ?></strong></p>
        </div>
    </section>
</body>
</html>

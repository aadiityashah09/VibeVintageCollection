<?php
session_start(); // Start the session to store cart data

// Check if the product_id is passed
if (isset($_POST['product_id']) && isset($_POST['product_name']) && isset($_POST['product_price'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    // If the cart doesn't exist, create it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    $product_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += 1; // Increase the quantity if already in the cart
            $product_exists = true;
            break;
        }
    }

    // If the product doesn't exist, add it to the cart
    if (!$product_exists) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => 1
        ];
    }

    // Redirect to the checkout page
    header('Location: checkout.php');
    exit();
}

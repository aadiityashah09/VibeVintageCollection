<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session to store cart data
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vibevintage";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding an item to the cart
if (isset($_GET['action']) && $_GET['action'] == 'add_to_cart' && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch the product details from the database
    $result = $conn->query("SELECT * FROM kids_collection WHERE id = $product_id");
    $product = $result->fetch_assoc();

    if ($product) {
        // Add product to the session cart
        $cart_item = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1
        ];

        // If the cart is empty, initialize it
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // If the product is already in the cart, increase its quantity
        $product_exists_in_cart = false;
        foreach ($_SESSION['cart'] as &$cart_product) {
            if ($cart_product['id'] == $product['id']) {
                $cart_product['quantity'] += 1;
                $product_exists_in_cart = true;
                break;
            }
        }

        // If the product is not in the cart, add it
        if (!$product_exists_in_cart) {
            $_SESSION['cart'][] = $cart_item;
        }

        // Redirect back to the product page to see the updated cart
        header('Location: kids.php');
        exit();
    }
}

// Fetch products from the kids collection
$result = $conn->query("SELECT mc.*, GROUP_CONCAT(pi.image_path) AS images FROM kids_collection mc LEFT JOIN product_images pi ON mc.id = pi.product_id GROUP BY mc.id");

// Calculate total cart price
$total_price = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
}

// Calculate shipping fee: ₹50 if total price < ₹1000, else ₹0 (free shipping)
$shipping_fee = ($total_price >= 1000) ? 0 : 50;

// Debug total price and shipping fee
echo "<p>Total Price: ₹" . $total_price . "</p>";
echo "<p>Shipping Fee: ₹" . $shipping_fee . "</p>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kids Collection - VibeVintage Collection</title>
    <link rel="stylesheet" href="mencss.css">
</head>
<body>
    <header>
        <h1>Kids Collection</h1>
        <!-- Display Cart Count -->
        <div class="cart">
            <a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
        </div>
        <nav>
            <ul>
                <li><a href="men.php"> Shop for Men's Collection</a></li>
                <li><a href="women.php"> Shop for Women's Collection</a></li>
            </ul>
        </nav>
    </header>

    <section class="product-collection">
        <div class="container">
            <h1>Explore Kids’ Fashion</h1>
            <div class="collection-grid">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="product-item">
                            <!-- Display product images -->
                            <div class="product-images">
                                <?php
                                $images = $row['images'] ? explode(',', $row['images']) : [];
                                foreach ($images as $image): ?>
                                    <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="product-image">
                                <?php endforeach; ?>
                            </div>
                            <!-- Display product details -->
                            <h3><?= htmlspecialchars($row['name']) ?></h3>
                            <p>₹<?= htmlspecialchars($row['price']) ?></p>
                            <p><?= htmlspecialchars($row['description']) ?></p>
                            <a href="?action=add_to_cart&product_id=<?= $row['id'] ?>" class="btn">Add to Cart</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No products available in the collection.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Free Shipping Offer -->
    <section class="free-shipping">
        <div class="container">
            <?php if ($total_price >= 1000): ?>
                <p><strong>Congratulations! You qualify for free shipping on your order.</strong></p>
            <?php else: ?>
                <p>Spend ₹<?= (1000 - $total_price) ?> more to get free shipping!</p>
                <p>Shipping Fee: ₹<?= $shipping_fee ?></p>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 VibeVintage Collection. All Rights Reserved.</p>
    </footer>
</body>
</html>

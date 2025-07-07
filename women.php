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
    $result = $conn->query("SELECT * FROM women_collection WHERE id = $product_id");
    $product = $result->fetch_assoc();

    if ($product) {
        // Apply Buy One Get One Free offer
        $cart_item = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'], // Price remains the same for both products
            'quantity' => 2  // Add 2 products instead of 1 due to the offer
        ];

        // If the cart is empty, initialize it
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // If the product is already in the cart, increase its quantity
        $product_exists_in_cart = false;
        foreach ($_SESSION['cart'] as &$cart_product) {
            if ($cart_product['id'] == $product['id']) {
                $cart_product['quantity'] += 2;  // Add 2 instead of 1 due to BOGO offer
                $product_exists_in_cart = true;
                break;
            }
        }

        // If the product is not in the cart, add it
        if (!$product_exists_in_cart) {
            $_SESSION['cart'][] = $cart_item;
        }

        // Redirect back to the product page to see the updated cart
        header('Location: women.php');
        exit();
    }
}

// Fetch products from the women's collection
$result = $conn->query("SELECT * FROM women_collection");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Women's Collection - VibeVintage</title>
    <link rel="stylesheet" href="mencss.css">
</head>
<body>
    <header>
        <h1>Women's Fashion Collection</h1>
        <!-- Display Cart Count -->
        <div class="cart">
            <a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
        </div>
        <nav>
            <ul>
                <li><a href="men.php"> Shop for Men's Collection</a></li>
                <li><a href="kids.php"> Shop for Kids' Collection</a></li>
            </ul>
        </nav>
    </header>
    <section class="product-collection">
        <div class="container">
            <h1>Our Latest Collection</h1>
            <div class="collection-grid">
                <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-item">
                    <img src="<?= htmlspecialchars($row["image1"]) ?>" alt="<?= htmlspecialchars($row["name"]) ?>">
                    <?php if (!empty($row["image2"])): ?>
                    <img src="<?= htmlspecialchars($row["image2"]) ?>" alt="<?= htmlspecialchars($row["name"]) ?>">
                    <?php endif; ?>
                    <h3 class="product-name"><?= htmlspecialchars($row["name"]) ?></h3>
                    <p class="product-price">₹<?= htmlspecialchars($row["price"]) ?></p>
                    <p class="product-description"><?= htmlspecialchars($row["description"]) ?></p>

                    <!-- Display the Buy One Get One Free offer -->
                    <p class="offer-message" style="color: green; font-weight: bold;">Buy One Get One Free Offer</p>

                    <!-- Add to Cart Link -->
                    <a href="?action=add_to_cart&product_id=<?= $row['id'] ?>" class="btn">Add to Cart</a>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2024 VibeVintage Collection. All Rights Reserved.</p>
    </footer>
</body>
</html>

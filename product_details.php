<?php
session_start();
include 'includes/header.php';
include 'includes/db_connect.php';

$product_id = $_GET['id'];
$product_query = "SELECT * FROM products WHERE product_id = '$product_id'";
$product_result = mysqli_query($conn, $product_query);
$product = mysqli_fetch_assoc($product_result);

// Add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = $_POST['quantity'];
    $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] ?? 0 + $quantity;
    header("Location: cart.php");
    exit();
}
?>

<h1><?php echo $product['name']; ?></h1>
<p><?php echo $product['description']; ?></p>
<p>Price: <?php echo $product['price']; ?></p>

<form method="post">
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" min="1" value="1">
    <button type="submit">Add to Cart</button>
</form>

<?php include 'includes/footer.php'; ?>

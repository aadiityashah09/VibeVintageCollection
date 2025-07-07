<?php
session_start();
include 'includes/admin_header.php';
include 'includes/db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Add a new product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $add_product_query = "INSERT INTO products (name, price, description) VALUES ('$name', '$price', '$description')";
    mysqli_query($conn, $add_product_query);
}

// Fetch all products
$products = mysqli_query($conn, "SELECT * FROM products");

// HTML and form for managing products
?>

<h1>Manage Products</h1>

<form method="POST" action="">
    <label>Product Name:</label><input type="text" name="name" required>
    <label>Price:</label><input type="number" name="price" required>
    <label>Description:</label><textarea name="description" required></textarea>
    <button type="submit" name="add_product">Add Product</button>
</form>

<h2>Product List</h2>
<table>
    <tr>
        <th>Name</th>
        <th>Price</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>
    <?php while ($product = mysqli_fetch_assoc($products)) { ?>
        <tr>
            <td><?php echo $product['name']; ?></td>
            <td><?php echo $product['price']; ?></td>
            <td><?php echo $product['description']; ?></td>
            <td>
                <a href="edit_product.php?id=<?php echo $product['product_id']; ?>">Edit</a>
                <a href="delete_product.php?id=<?php echo $product['product_id']; ?>">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>

<?php include 'includes/admin_footer.php'; ?>

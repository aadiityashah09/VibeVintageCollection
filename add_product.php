<?php
// Check if the admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Handle form submission (e.g., add new product)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assume all data is valid and insert into the database
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image = $_POST['image']; // In a real scenario, handle image uploads properly

    // SQL insert logic here
    // Example: $sql = "INSERT INTO products (name, category, price, image) VALUES ('$name', '$category', '$price', '$image')";
    
    header('Location: admin_panel.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - VibeVintage Collection</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <header class="admin-header">
        <h1>VibeVintage Collection Admin Panel</h1>
        <nav>
            <ul>
                <li><a href="admin_panel.php">Dashboard</a></li>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <section class="admin-add-product">
            <h2>Add New Product</h2>
            <form method="POST" action="add_product.php" enctype="multipart/form-data">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>

                <select name="category" required>
    <option value="men">Men</option>
    <option value="women">Women</option>
    <option value="kids">Kids</option>
</select>

            

                <label for="price">Price:</label>
                <input type="text" id="price" name="price" required>

                <label for="image">Image URL:</label>
                <input type="text" id="image" name="image" required>

                <button type="submit" class="btn">Add Product</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 VibeVintage Collection. All Rights Reserved.</p>
    </footer>
</body>
</html>

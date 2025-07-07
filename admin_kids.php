<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vibevintage";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding a new product with multiple images
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_product"])) {
    $name = $conn->real_escape_string($_POST["product_name"]);
    $price = $conn->real_escape_string($_POST["product_price"]);
    $description = $conn->real_escape_string($_POST["product_description"]);

    $sql = "INSERT INTO kids_collection (name, price, description) VALUES ('$name', '$price', '$description')";
    if ($conn->query($sql)) {
        $product_id = $conn->insert_id;

        if (isset($_FILES['product_images'])) {
            $files = $_FILES['product_images'];
            for ($i = 0; $i < count($files['name']); $i++) {
                $image_name = $files['name'][$i];
                $image_tmp = $files['tmp_name'][$i];
                $upload_path = "uploads/" . $image_name;

                if (move_uploaded_file($image_tmp, $upload_path)) {
                    $conn->query("INSERT INTO product_images (product_id, image_path) VALUES ('$product_id', '$upload_path')");
                }
            }
        }
    }
}

// Handle updating a product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_product"])) {
    $product_id = $conn->real_escape_string($_POST["update_product_id"]);
    $name = $conn->real_escape_string($_POST["update_product_name"]);
    $price = $conn->real_escape_string($_POST["update_product_price"]);
    $description = $conn->real_escape_string($_POST["update_product_description"]);

    $sql = "UPDATE kids_collection SET name='$name', price='$price', description='$description' WHERE id=$product_id";
    $conn->query($sql);
}

// Handle deleting a product and its associated images
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_product"])) {
    $product_id = $_POST["product_id"];

    $result = $conn->query("SELECT image_path FROM product_images WHERE product_id = $product_id");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if (file_exists($row['image_path'])) {
                unlink($row['image_path']);
            }
        }
    }

    $conn->query("DELETE FROM kids_collection WHERE id = $product_id");
}

// Fetch products with their images
$result = $conn->query("
    SELECT kc.*, GROUP_CONCAT(pi.image_path) AS images 
    FROM kids_collection kc 
    LEFT JOIN product_images pi ON kc.id = pi.product_id 
    GROUP BY kc.id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Kids Collection</title>
    <link rel="stylesheet" href="admin.css">
    <script>
        function showUpdateModal(id, name, price, description) {
            document.getElementById('update_product_id').value = id;
            document.getElementById('update_product_name').value = name;
            document.getElementById('update_product_price').value = price;
            document.getElementById('update_product_description').value = description;
            document.getElementById('updateModal').style.display = 'block';
        }

        function closeUpdateModal() {
            document.getElementById('updateModal').style.display = 'none';
        }
    </script>
</head>
<body>
    <header>
        <h1>Admin Panel - Kids Collection</h1>
    </header>
    <section class="admin-section">
        <h2>Add New Product</h2>
        <form method="POST" enctype="multipart/form-data" class="admin-form">
            <input type="text" name="product_name" placeholder="Product Name" required>
            <input type="text" name="product_price" placeholder="Product Price" required>
            <textarea name="product_description" placeholder="Product Description" required></textarea>
            <input type="file" name="product_images[]" multiple required>
            <button type="submit" name="add_product">Add Product</button>
        </form>

        <h2>Manage Products</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Images</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row["id"] ?></td>
                            <td>
                                <?php 
                                $images = $row['images'] ? explode(',', $row['images']) : [];
                                foreach ($images as $image): ?>
                                    <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($row['name']) ?>" width="50">
                                <?php endforeach; ?>
                            </td>
                            <td><?= htmlspecialchars($row["name"]) ?></td>
                            <td>₹<?= htmlspecialchars($row["price"]) ?></td>
                            <td><?= htmlspecialchars($row["description"]) ?></td>
                            <td>
                                <button onclick="showUpdateModal(
                                    <?= $row['id'] ?>, 
                                    '<?= htmlspecialchars($row['name']) ?>', 
                                    '<?= htmlspecialchars($row['price']) ?>', 
                                    '<?= htmlspecialchars($row['description']) ?>')">Update</button>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="product_id" value="<?= $row["id"] ?>">
                                    <button type="submit" name="delete_product" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No products available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <!-- Update Modal -->
    <div id="updateModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; border:1px solid #ccc; z-index:1000;">
        <h2>Update Product</h2>
        <form method="POST" class="admin-form">
            <input type="hidden" name="update_product_id" id="update_product_id">
            <input type="text" name="update_product_name" id="update_product_name" placeholder="Product Name" required>
            <input type="text" name="update_product_price" id="update_product_price" placeholder="Product Price" required>
            <textarea name="update_product_description" id="update_product_description" placeholder="Product Description" required></textarea>
            <button type="submit" name="update_product">Update Product</button>
            <button type="button" onclick="closeUpdateModal()">Cancel</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 VibeVintage Collection. Admin Panel.</p>
    </footer>
</body>
</html>

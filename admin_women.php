<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vibevintage";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_product"])) {
    $product_id = $_POST["product_id"];

    $image_result = $conn->query("SELECT image1, image2 FROM women_collection WHERE id = $product_id");
    if ($image_row = $image_result->fetch_assoc()) {
        foreach (['image1', 'image2'] as $image) {
            if (!empty($image_row[$image]) && file_exists($image_row[$image])) {
                unlink($image_row[$image]);
            }
        }
    }

    $conn->query("DELETE FROM women_collection WHERE id = $product_id");
    header("Location: admin_women.php");
    exit();
}

// Add product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_product"])) {
    $name = $conn->real_escape_string($_POST["product_name"]);
    $price = $conn->real_escape_string($_POST["product_price"]);
    $description = $conn->real_escape_string($_POST["product_description"]);

    $image1 = "";
    $image2 = "";

    if (!empty($_FILES['product_image1']['name'])) {
        $image1 = "uploads/" . basename($_FILES['product_image1']['name']);
        move_uploaded_file($_FILES['product_image1']['tmp_name'], $image1);
    }
    if (!empty($_FILES['product_image2']['name'])) {
        $image2 = "uploads/" . basename($_FILES['product_image2']['name']);
        move_uploaded_file($_FILES['product_image2']['tmp_name'], $image2);
    }

    $sql = "INSERT INTO women_collection (name, price, image1, image2, description)
            VALUES ('$name', '$price', '$image1', '$image2', '$description')";
    $conn->query($sql);
}

// Update product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_product"])) {
    $id = $conn->real_escape_string($_POST["update_product_id"]);
    $name = $conn->real_escape_string($_POST["update_product_name"]);
    $price = $conn->real_escape_string($_POST["update_product_price"]);
    $description = $conn->real_escape_string($_POST["update_product_description"]);

    $sql = "UPDATE women_collection SET name='$name', price='$price', description='$description' WHERE id=$id";
    $conn->query($sql);
}

// Fetch all products
$result = $conn->query("SELECT * FROM women_collection");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Women's Collection</title>
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
        <h1>Admin Panel - Women's Collection</h1>
    </header>

    <section class="admin-section">
        <h2>Add New Product</h2>
        <form method="POST" enctype="multipart/form-data" class="admin-form">
            <input type="text" name="product_name" placeholder="Product Name" required>
            <input type="text" name="product_price" placeholder="Product Price" required>
            <textarea name="product_description" placeholder="Product Description" required></textarea>
            <label>Upload Image 1:</label>
            <input type="file" name="product_image1" required>
            <label>Upload Image 2 (optional):</label>
            <input type="file" name="product_image2">
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
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td>
                        <?php if (!empty($row["image1"])): ?>
                            <img src="<?= htmlspecialchars($row["image1"]) ?>" width="50">
                        <?php endif; ?>
                        <?php if (!empty($row["image2"])): ?>
                            <img src="<?= htmlspecialchars($row["image2"]) ?>" width="50">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row["name"]) ?></td>
                    <td>₹<?= htmlspecialchars($row["price"]) ?></td>
                    <td><?= htmlspecialchars($row["description"]) ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $row["id"] ?>">
                            <button type="submit" name="delete_product">Delete</button>
                        </form>
                        <button onclick="showUpdateModal('<?= $row['id'] ?>', '<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>', '<?= $row['price'] ?>', '<?= htmlspecialchars($row['description'], ENT_QUOTES) ?>')">Update</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <!-- Update Modal -->
    <div id="updateModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span onclick="closeUpdateModal()" style="float:right; cursor:pointer;">&times;</span>
            <h3>Update Product</h3>
            <form method="POST" class="admin-form">
                <input type="hidden" name="update_product_id" id="update_product_id">
                <input type="text" name="update_product_name" id="update_product_name" required>
                <input type="text" name="update_product_price" id="update_product_price" required>
                <textarea name="update_product_description" id="update_product_description" required></textarea>
                <button type="submit" name="update_product">Save Changes</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 VibeVintage Collection. Admin Panel.</p>
    </footer>
</body>
</html>

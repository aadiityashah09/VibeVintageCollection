<?php
session_start();
include 'includes/admin_header.php';
include 'includes/db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch all orders
$orders = mysqli_query($conn, "SELECT * FROM orders JOIN users ON orders.user_id = users.user_id");

?>

<h1>Manage Orders</h1>
<table>
    <tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Total</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php while ($order = mysqli_fetch_assoc($orders)) { ?>
        <tr>
            <td><?php echo $order['order_id']; ?></td>
            <td><?php echo $order['name']; ?></td>
            <td><?php echo $order['total']; ?></td>
            <td><?php echo $order['status']; ?></td>
            <td>
                <a href="update_order_status.php?id=<?php echo $order['order_id']; ?>">Update Status</a>
            </td>
        </tr>
    <?php } ?>
</table>

<?php include 'includes/admin_footer.php'; ?>

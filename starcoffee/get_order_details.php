<?php
// Start session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo "Unauthorized access";
    exit;
}

// Check if order ID is provided
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    echo "No order ID provided";
    exit;
}

$order_id = $_GET['order_id'];

// Connect to database
$conn = new mysqli("localhost", "root", "", "starcoffee_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get order information
$sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Order not found";
    exit;
}

$order = $result->fetch_assoc();

// Get order items
$sql = "SELECT * FROM order_items WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();
?>

<h2>Order #<?php echo $order_id; ?> Details</h2>

<div class="order-details-grid">
    <div>
        <h3>Customer Information</h3>
        <p><strong>Name:</strong> <?php echo $order['customer_name']; ?></p>
        <p><strong>Email:</strong> <?php echo $order['customer_email']; ?></p>
        <p><strong>Phone:</strong> <?php echo $order['customer_phone']; ?></p>
        <p><strong>Address:</strong> <?php echo $order['customer_address']; ?></p>
    </div>
    
    <div>
        <h3>Order Information</h3>
        <p><strong>Order Date:</strong> <?php echo date('M d, Y h:i A', strtotime($order['order_date'])); ?></p>
        <p><strong>Status:</strong> <span class="status status-<?php echo $order['order_status']; ?>"><?php echo ucfirst($order['order_status']); ?></span></p>
        <p><strong>Total Amount:</strong> ₱<?php echo number_format($order['order_total'], 2); ?></p>
    </div>
</div>

<h3>Order Items</h3>
<?php if ($items_result->num_rows > 0): ?>
    <table class="order-items-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $items_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $item['product_name']; ?></td>
                    <td>₱<?php echo number_format($item['product_price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₱<?php echo number_format($item['item_total'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No items found for this order.</p>
<?php endif; ?>

<div class="status-update-form">
    <h3>Update Order Status</h3>
    <form method="post" action="admin_dashboard.php">
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        <select name="new_status">
            <option value="pending" <?php echo $order['order_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="processing" <?php echo $order['order_status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
            <option value="completed" <?php echo $order['order_status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
            <option value="cancelled" <?php echo $order['order_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
        </select>
        <button type="submit" name="update_status" class="update-status-btn">Update Status</button>
    </form>
</div>

<?php
// Close database connection
$conn->close();
?>
<?php
// Start session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "starcoffee_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle filter
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];
    
    $sql = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $order_id);
    
    if ($stmt->execute()) {
        $status_message = "Order #" . $order_id . " status updated to " . $new_status;
    } else {
        $error_message = "Error updating order status: " . $conn->error;
    }
}

// Query to get orders based on filter
$sql = "SELECT * FROM orders WHERE 1=1";

if ($status_filter !== 'all') {
    $sql .= " AND order_status = '$status_filter'";
}

if (!empty($search)) {
    $sql .= " AND (order_id LIKE '%$search%' OR customer_name LIKE '%$search%' OR customer_email LIKE '%$search%')";
}

$sql .= " ORDER BY order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - StarCoffee</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 120px auto 50px;
            padding: 20px;
        }
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .filter-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .filter-btn {
            padding: 10px 15px;
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .filter-btn.active {
            background-color: #7E6A56;
            color: white;
            border-color: #7E6A56;
        }
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-form input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-form button {
            padding: 10px 15px;
            background-color: #7E6A56;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .orders-table th, .orders-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .orders-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .orders-table tr:hover {
            background-color: #f9f9f9;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: bold;
        }
        .status-pending {
            background-color: #ffeeba;
            color: #856404;
        }
        .status-processing {
            background-color: #b8daff;
            color: #004085;
        }
        .status-completed {
            background-color: #c3e6cb;
            color: #155724;
        }
        .status-cancelled {
            background-color: #f5c6cb;
            color: #721c24;
        }
        .action-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            margin-right: 5px;
            font-size: 14px;
        }
        .view-btn {
            background-color: #17a2b8;
        }
        .logout-btn {
            padding: 8px 15px;
            background-color: #f5c6cb;
            color: #721c24;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 10px;
            width: 80%;
            max-width: 800px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
        .order-details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        .order-items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-items-table th, .order-items-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .status-update-form {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .status-update-form select {
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .update-status-btn {
            padding: 8px 15px;
            background-color: #7E6A56;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .no-orders {
            text-align: center;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 10px;
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <div class="dashboard-container">
            <div class="dashboard-header">
                <h2 class="section__title">ADMIN DASHBOARD</h2>
                <a href="admin_logout.php" class="logout-btn">LOGOUT</a>
            </div>
            
            <?php if (isset($status_message)): ?>
                <div class="alert alert-success"><?php echo $status_message; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <div class="filter-container">
                <a href="admin_dashboard.php" class="filter-btn <?php echo $status_filter === 'all' ? 'active' : ''; ?>">All Orders</a>
                <a href="admin_dashboard.php?status=pending" class="filter-btn <?php echo $status_filter === 'pending' ? 'active' : ''; ?>">Pending</a>
                <a href="admin_dashboard.php?status=processing" class="filter-btn <?php echo $status_filter === 'processing' ? 'active' : ''; ?>">Processing</a>
                <a href="admin_dashboard.php?status=completed" class="filter-btn <?php echo $status_filter === 'completed' ? 'active' : ''; ?>">Completed</a>
                <a href="admin_dashboard.php?status=cancelled" class="filter-btn <?php echo $status_filter === 'cancelled' ? 'active' : ''; ?>">Cancelled</a>
            </div>
            
            <form class="search-form" method="get" action="">
                <?php if ($status_filter !== 'all'): ?>
                    <input type="hidden" name="status" value="<?php echo $status_filter; ?>">
                <?php endif; ?>
                <input type="text" name="search" placeholder="Search by order ID, customer name or email..." value="<?php echo $search; ?>">
                <button type="submit">Search</button>
            </form>
            
            <?php if ($result->num_rows > 0): ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['order_id']; ?></td>
                                <td><?php echo $row['customer_name']; ?></td>
                                <td><?php echo $row['customer_email']; ?></td>
                                <td>₱<?php echo number_format($row['order_total'], 2); ?></td>
                                <td><?php echo date('M d, Y h:i A', strtotime($row['order_date'])); ?></td>
                                <td>
                                    <span class="status status-<?php echo $row['order_status']; ?>">
                                        <?php echo ucfirst($row['order_status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="action-btn view-btn" onclick="viewOrder(<?php echo $row['order_id']; ?>)">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-orders">
                    <p>No orders found.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <!-- Order Details Modal -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="orderDetails"></div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    
    <script>
        // Function to view order details
        function viewOrder(orderId) {
            // Fetch order details via AJAX
            fetch('get_order_details.php?order_id=' + orderId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('orderDetails').innerHTML = data;
                    document.getElementById('orderModal').style.display = 'block';
                })
                .catch(error => console.error('Error fetching order details:', error));
        }
        
        // Function to close modal
        function closeModal() {
            document.getElementById('orderModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('orderModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
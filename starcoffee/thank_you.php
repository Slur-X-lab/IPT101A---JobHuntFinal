<?php
// Start session
session_start();

// Get order ID from URL
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// If no order ID, redirect to home
if ($order_id <= 0) {
    header("Location: index.html");
    exit;
}

// Connect to database to get order details
$conn = new mysqli("localhost", "root", "", "starcoffee_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get order details
$sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

// Get order items
$sql = "SELECT * FROM order_items WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();
$items = [];
while ($row = $items_result->fetch_assoc()) {
    $items[] = $row;
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - StarCoffee</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .thankyou-container {
            max-width: 800px;
            margin: 120px auto 50px;
            padding: 20px;
            text-align: center;
        }
        .order-confirmation {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .order-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 15px;
            text-align: left;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .order-total {
            font-weight: bold;
            margin-top: 20px;
            text-align: right;
            font-size: 18px;
        }
        .customer-details {
            margin-top: 20px;
            text-align: left;
        }
        .home-btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #7E6A56;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Header with navigation -->
    <header class="header" id="header">
        <!-- Your existing header code -->
    </header>

    <main>
        <div class="thankyou-container">
            <div class="order-confirmation">
                <i class="ri-checkbox-circle-fill" style="font-size: 60px; color: #4CAF50;"></i>
                <h2>Thank You for Your Order!</h2>
                <p>Your order #<?php echo $order_id; ?> has been received and is being processed.</p>
                <p>A confirmation email has been sent to <?php echo $order['customer_email']; ?></p>
            </div>
            
            <div class="order-details">
                <h3>Order Summary</h3>
                
                <?php foreach ($items as $item): ?>
                    <div class="order-item">
                        <div>
                            <p><?php echo $item['product_name']; ?> x <?php echo $item['quantity']; ?></p>
                        </div>
                        <div>
                            <p>₱<?php echo number_format($item['item_total'], 2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="order-total">
                    <p>Total: ₱<?php echo number_format($order['order_total'], 2); ?></p>
                </div>
                
                <div class="customer-details">
                    <h3>Delivery Information</h3>
                    <p><strong>Name:</strong> <?php echo $order['customer_name']; ?></p>
                    <p><strong>Phone:</strong> <?php echo $order['customer_phone']; ?></p>
                    <p><strong>Address:</strong> <?php echo $order['customer_address']; ?></p>
                </div>
            </div>
            
            <a href="index.html" class="home-btn">RETURN TO HOME</a>
        </div>
    </main>
</body>
</html>
<?php
// Start session to access cart data
session_start();

// Calculate order total
$total = 0;
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach ($_SESSION['cart'] as $product => $details) {
        $total += $details['price'] * $details['quantity'];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    
    // Validate form data (add more validation as needed)
    $errors = [];
    if (empty($name)) $errors[] = "Name is required";
    if (empty($email)) $errors[] = "Email is required";
    if (empty($phone)) $errors[] = "Phone is required";
    if (empty($address)) $errors[] = "Address is required";
    
    // If no errors and cart is not empty, process the order
    if (empty($errors) && !empty($_SESSION['cart'])) {
        // Connect to database
        $conn = new mysqli("localhost", "root", "", "starcoffee_db");
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Create order in orders table
        $sql = "INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, order_total, order_date) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssd", $name, $email, $phone, $address, $total);
        $stmt->execute();
        
        $order_id = $stmt->insert_id;
        
        // Insert order items
        foreach ($_SESSION['cart'] as $product => $details) {
            $item_price = $details['price'];
            $item_quantity = $details['quantity'];
            $item_total = $item_price * $item_quantity;
            
            $sql = "INSERT INTO order_items (order_id, product_name, product_price, quantity, item_total) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isdid", $order_id, $product, $item_price, $item_quantity, $item_total);
            $stmt->execute();
        }
        
        // Close database connection
        $conn->close();
        
        // Clear the cart
        $_SESSION['cart'] = array();
        
        // Redirect to thank you page
        header("Location: thank_you.php?order_id=" . $order_id);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - StarCoffee</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .checkout-container {
            max-width: 900px;
            margin: 120px auto 50px;
            padding: 20px;
        }
        .order-summary, .customer-info {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
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
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
        .submit-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #7E6A56;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Header with navigation -->
    <header class="header" id="header">
        <!-- Your existing header code -->
    </header>

    <main>
        <div class="checkout-container">
            <h2 class="section__title">CHECKOUT</h2>
            
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="empty-cart">
                    <p>Your cart is empty. <a href="order.php">Continue shopping</a></p>
                </div>
            <?php else: ?>
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    
                    <?php foreach ($_SESSION['cart'] as $product => $details): ?>
                        <?php if ($details['quantity'] > 0): ?>
                            <div class="order-item">
                                <div>
                                    <p><?php echo $product; ?> x <?php echo $details['quantity']; ?></p>
                                </div>
                                <div>
                                    <p>₱<?php echo number_format($details['price'] * $details['quantity'], 2); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    
                    <div class="order-total">
                        <p>Total: ₱<?php echo number_format($total, 2); ?></p>
                    </div>
                </div>
                
                <div class="customer-info">
                    <h3>Customer Information</h3>
                    
                    <?php if (!empty($errors)): ?>
                        <div class="errors">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li class="error"><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo isset($phone) ? $phone : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Delivery Address</label>
                            <textarea id="address" name="address" rows="4" required><?php echo isset($address) ? $address : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="submit-btn">PLACE ORDER</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
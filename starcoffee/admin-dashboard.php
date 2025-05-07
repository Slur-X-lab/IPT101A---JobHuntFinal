<?php
session_start();

// Check if user is logged in, redirect to login page if not
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit;
}

// Handle logout
if(isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: admin-login.php");
    exit;
}

// Get a count of mock orders for demo purposes
$orderCount = rand(10, 30);
$newOrdersCount = rand(1, 5);

// Get total revenue (mock data for demo)
$totalRevenue = rand(15000, 50000);

// Sample products array (in a real app, this would come from a database)
$products = [
    [
        'id' => 1,
        'name' => 'ICED COFFEE MOCHA',
        'price' => 49.90,
        'stock' => 45,
        'image' => 'assets/img/products-coffee-1.png'
    ],
    [
        'id' => 2,
        'name' => 'COFFEE WITH CREAM',
        'price' => 59.90,
        'stock' => 32,
        'image' => 'assets/img/products-coffee-2.png'
    ],
    [
        'id' => 3,
        'name' => 'CAPPUCCINO COFFEE',
        'price' => 69.90,
        'stock' => 28,
        'image' => 'assets/img/products-coffee-3.png'
    ],
    [
        'id' => 4,
        'name' => 'COFFEE WITH MILK',
        'price' => 79.90,
        'stock' => 36,
        'image' => 'assets/img/products-coffee-4.png'
    ],
    [
        'id' => 5,
        'name' => 'CLASSIC ICED COFFEE',
        'price' => 89.90,
        'stock' => 40,
        'image' => 'assets/img/products-coffee-5.png'
    ],
    [
        'id' => 6,
        'name' => 'ICED COFFEE FRAPPE',
        'price' => 99.90,
        'stock' => 25,
        'image' => 'assets/img/products-coffee-6.png'
    ],
    [
        'id' => 7,
        'name' => 'VANILLA LATTE',
        'price' => 89.90,
        'stock' => 38,
        'image' => 'assets/img/popular-coffee-1.png'
    ],
    [
        'id' => 8,
        'name' => 'CLASSIC COFFEE',
        'price' => 69.90,
        'stock' => 42,
        'image' => 'assets/img/popular-coffee-2.png'
    ],
    [
        'id' => 9,
        'name' => 'MOCHA COFFEE',
        'price' => 109.90,
        'stock' => 20,
        'image' => 'assets/img/popular-coffee-3.png'
    ]
];

// Sample recent orders (mock data for demonstration)
$recentOrders = [
    [
        'id' => 'ORD-' . rand(1000, 9999),
        'customer' => 'John Doe',
        'date' => date('Y-m-d H:i:s', strtotime('-1 hour')),
        'total' => rand(100, 500),
        'status' => 'Pending'
    ],
    [
        'id' => 'ORD-' . rand(1000, 9999),
        'customer' => 'Jane Smith',
        'date' => date('Y-m-d H:i:s', strtotime('-3 hours')),
        'total' => rand(100, 500),
        'status' => 'Processing'
    ],
    [
        'id' => 'ORD-' . rand(1000, 9999),
        'customer' => 'Robert Johnson',
        'date' => date('Y-m-d H:i:s', strtotime('-5 hours')),
        'total' => rand(100, 500),
        'status' => 'Completed'
    ],
    [
        'id' => 'ORD-' . rand(1000, 9999),
        'customer' => 'Emily Wilson',
        'date' => date('Y-m-d H:i:s', strtotime('-1 day')),
        'total' => rand(100, 500),
        'status' => 'Completed'
    ],
    [
        'id' => 'ORD-' . rand(1000, 9999),
        'customer' => 'Michael Brown',
        'date' => date('Y-m-d H:i:s', strtotime('-1 day')),
        'total' => rand(100, 500),
        'status' => 'Completed'
    ]
];

// Get current active tab
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
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
        /* Dashboard specific styles */
        body {
            background-color: #f5f5f5;
            font-family: 'Arial', sans-serif;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar styles */
        .sidebar {
            width: 250px;
            background-color: #7E6A56;
            color: white;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar-logo {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(255,255,255,0.1);
            border-left: 4px solid white;
        }
        
        .sidebar-menu a i {
            margin-right: 10px;
            min-width: 25px;
            text-align: center;
        }
        
        /* Main content styles */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .header h1 {
            font-size: 24px;
            color: #333;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-info span {
            margin-right: 15px;
        }
        
        .logout-btn {
            padding: 8px 15px;
            background-color: #ff6b6b;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        
        /* Dashboard cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .card-title {
            font-size: 16px;
            color: #666;
            margin: 0;
        }
        
        .card-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            font-size: 20px;
        }
        
        .bg-blue {
            background-color: #4e73df;
        }
        
        .bg-green {
            background-color: #1cc88a;
        }
        
        .bg-orange {
            background-color: #f6c23e;
        }
        
        .card-value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        
        /* Tables */
        .table-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            overflow-x: auto;
        }
        
        .table-container h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 18px;
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        table th {
            background-color: #f9f9f9;
            font-weight: bold;
            color: #333;
        }
        
        table tr:hover {
            background-color: #f5f5f5;
        }
        
        /* Status badges */
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }
        
        .status-pending {
            background-color: #f6c23e;
        }
        
        .status-processing {
            background-color: #4e73df;
        }
        
        .status-completed {
            background-color: #1cc88a;
        }
        
        /* Product management */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .product-item {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            text-align: center;
        }
        
        .product-item img {
            max-width: 100px;
            margin-bottom: 10px;
        }
        
        .product-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        
        .btn-edit {
            background-color: #4e73df;
            color: white;
        }
        
        .btn-delete {
            background-color: #e74a3b;
            color: white;
        }
        
        .btn-primary {
            background-color: #7E6A56;
            color: white;
            padding: 10px 20px;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        /* Form styles */
        .form-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #7E6A56;
        }
        
        textarea.form-control {
            min-height: 100px;
        }
        
        /* Utilities */
        .text-success {
            color: #1cc88a;
        }
        
        .text-danger {
            color: #e74a3b;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Image preview */
        .img-preview {
            max-width: 150px;
            max-height: 150px;
            margin-top: 10px;
        }
        
        /* Low stock warning */
        .low-stock {
            color: #e74a3b;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <h2>STARCOFFEE ADMIN</h2>
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="?tab=dashboard" class="<?php echo $activeTab == 'dashboard' ? 'active' : ''; ?>">
                        <i class="ri-dashboard-fill"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="?tab=products" class="<?php echo $activeTab == 'products' ? 'active' : ''; ?>">
                        <i class="ri-cup-fill"></i> Products
                    </a>
                </li>
                <li>
                    <a href="?tab=orders" class="<?php echo $activeTab == 'orders' ? 'active' : ''; ?>">
                        <i class="ri-shopping-bag-fill"></i> Orders
                    </a>
                </li>
                <li>
                    <a href="?tab=customers" class="<?php echo $activeTab == 'customers' ? 'active' : ''; ?>">
                        <i class="ri-user-fill"></i> Customers
                    </a>
                </li>
                <li>
                    <a href="?tab=settings" class="<?php echo $activeTab == 'settings' ? 'active' : ''; ?>">
                        <i class="ri-settings-fill"></i> Settings
                    </a>
                </li>
                <li>
                    <a href="index.php" target="_blank">
                        <i class="ri-external-link-fill"></i> View Website
                    </a>
                </li>
            </ul>
        </aside>
        
        <!-- Main content area -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <h1>
                    <?php 
                    switch($activeTab) {
                        case 'dashboard':
                            echo "Dashboard";
                            break;
                        case 'products':
                            echo "Product Management";
                            break;
                        case 'orders':
                            echo "Order Management";
                            break;
                        case 'customers':
                            echo "Customer Management";
                            break;
                        case 'settings':
                            echo "Settings";
                            break;
                        default:
                            echo "Dashboard";
                    }
                    ?>
                </h1>
                <div class="user-info">
                    <span>Welcome, <?php echo $_SESSION['admin_username']; ?></span>
                    <a href="?logout=1" class="logout-btn">Logout</a>
                </div>
            </div>
            
            <!-- Content based on active tab -->
            <?php if($activeTab == 'dashboard'): ?>
                <!-- Dashboard content -->
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">TOTAL ORDERS</h3>
                            <div class="card-icon bg-blue">
                                <i class="ri-shopping-bag-fill"></i>
                            </div>
                        </div>
                        <p class="card-value"><?php echo $orderCount; ?></p>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">NEW ORDERS</h3>
                            <div class="card-icon bg-orange">
                                <i class="ri-notification-3-fill"></i>
                            </div>
                        </div>
                        <p class="card-value"><?php echo $newOrdersCount; ?></p>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">REVENUE</h3>
                            <div class="card-icon bg-green">
                                <i class="ri-money-peso-circle-fill"></i>
                            </div>
                        </div>
                        <p class="card-value">₱<?php echo number_format($totalRevenue, 2); ?></p>
                    </div>
                </div>
                
                <div class="table-container">
                    <h2>Recent Orders</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentOrders as $order): ?>
                                <tr>
                                    <td><?php echo $order['id']; ?></td>
                                    <td><?php echo $order['customer']; ?></td>
                                    <td><?php echo $order['date']; ?></td>
                                    <td>₱<?php echo number_format($order['total'], 2); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                            <?php echo $order['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="?tab=orders&action=view&id=<?php echo $order['id']; ?>" class="btn btn-edit">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="table-container">
                    <h2>Low Stock Products</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $lowStockCount = 0;
                            foreach($products as $product): 
                                if($product['stock'] < 30):
                                    $lowStockCount++;
                            ?>
                                <tr>
                                    <td><?php echo $product['name']; ?></td>
                                    <td>₱<?php echo number_format($product['price'], 2); ?></td>
                                    <td class="<?php echo $product['stock'] < 20 ? 'low-stock' : ''; ?>">
                                        <?php echo $product['stock']; ?> units
                                    </td>
                                    <td>
                                        <a href="?tab=products&action=edit&id=<?php echo $product['id']; ?>" class="btn btn-edit">Update Stock</a>
                                    </td>
                                </tr>
                            <?php 
                                endif;
                            endforeach;
                            
                            if($lowStockCount == 0):
                            ?>
                                <tr>
                                    <td colspan="4" class="text-center">No products with low stock</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
            <?php elseif($activeTab == 'products'): ?>
                <!-- Products content -->
                <a href="?tab=products&action=add" class="btn btn-primary">Add New Product</a>
                
                <?php if(isset($_GET['action']) && $_GET['action'] == 'add'): ?>
                    <!-- Add product form -->
                    <div class="form-container">
                        <h2>Add New Product</h2>
                        <form action="?tab=products" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="product_price">Price (₱)</label>
                                <input type="number" step="0.01" class="form-control" id="product_price" name="product_price" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="product_stock">Stock</label>
                                <input type="number" class="form-control" id="product_stock" name="product_stock" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="product_description">Description</label>
                                <textarea class="form-control" id="product_description" name="product_description"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="product_image">Product Image</label>
                                <input type="file" class="form-control" id="product_image" name="product_image">
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Add Product</button>
                        </form>
                    </div>
                    
                <?php elseif(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])): ?>
                    <?php 
                    $productId = $_GET['id'];
                    $currentProduct = null;
                    
                    // Find the product by ID
                    foreach($products as $product) {
                        if($product['id'] == $productId) {
                            $currentProduct = $product;
                            break;
                        }
                    }
                    
                    if($currentProduct):
                    ?>
                    <!-- Edit product form -->
                    <div class="form-container">
                        <h2>Edit Product: <?php echo $currentProduct['name']; ?></h2>
                        <form action="?tab=products" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="product_id" value="<?php echo $currentProduct['id']; ?>">
                            
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $currentProduct['name']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="product_price">Price (₱)</label>
                                <input type="number" step="0.01" class="form-control" id="product_price" name="product_price" value="<?php echo $currentProduct['price']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="product_stock">Stock</label>
                                <input type="number" class="form-control" id="product_stock" name="product_stock" value="<?php echo $currentProduct['stock']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="product_description">Description</label>
                                <textarea class="form-control" id="product_description" name="product_description">Delicious coffee product</textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="product_image">Current Image</label>
                                <div>
                                    <img src="<?php echo $currentProduct['image
<?php
// Start session to access cart data
session_start();

// Check if the request is POST and has required data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product']) && isset($_POST['quantity']) && isset($_POST['price'])) {
    $product = $_POST['product'];
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];
    
    // If quantity is 0, remove from cart
    if ($quantity <= 0) {
        if (isset($_SESSION['cart'][$product])) {
            unset($_SESSION['cart'][$product]);
        }
    } else {
        // Update or add to cart
        $_SESSION['cart'][$product] = array(
            'price' => $price,
            'quantity' => $quantity
        );
    }
    
    // Return success response
    echo json_encode(['success' => true]);
} else {
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
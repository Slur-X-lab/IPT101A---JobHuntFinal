<?php
// Start session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Check if request is POST and contains required data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    // Connect to database
    $conn = new mysqli("localhost", "root", "", "starcoffee_db");
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];
    
    // Validate status
    $allowed_statuses = ['pending', 'processing', 'completed', 'cancelled'];
    if (!in_array($new_status, $allowed_statuses)) {
        $_SESSION['error_message'] = "Invali
<?php
session_start();

// Database Configuration
$host = "localhost:3307";
$username = "root";
$password = "";
$database = "plantopia";

// Create Connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check Connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Generate session ID if not exists
if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = session_id();
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Function to get current user data
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'phone' => $_SESSION['user_phone']
        ];
    }
    return null;
}

// Function to require login (redirect to login page if not logged in)
function requireLogin($redirect_url = '') {
    if (!isLoggedIn()) {
        $login_url = 'login.php';
        if (!empty($redirect_url)) {
            $login_url .= '?redirect=' . urlencode($redirect_url);
        }
        header('Location: ' . $login_url);
        exit;
    }
}

// Function to get cart count
function getCartCount($conn, $session_id) {
    $query = "SELECT SUM(quantity) as total FROM cart WHERE session_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $session_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ? $row['total'] : 0;
}

// Function to get cart total
function getCartTotal($conn, $session_id) {
    $query = "SELECT SUM(c.quantity * p.price) as total 
              FROM cart c 
              JOIN products p ON c.product_id = p.id 
              WHERE c.session_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $session_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ? $row['total'] : 0;
}

// Function to create users table if it doesn't exist
function createUsersTable($conn) {
    $query = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        phone VARCHAR(20),
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (!mysqli_query($conn, $query)) {
        error_log("Error creating users table: " . mysqli_error($conn));
    }
}

// Create users table if it doesn't exist
createUsersTable($conn);
?>
<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']) ?: 1;
    $session_id = $_SESSION['session_id'];
    
    // Check if product exists
    $check_product = "SELECT id FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $check_product);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 0) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
    
    // Check if item already in cart
    $check_cart = "SELECT id, quantity FROM cart WHERE product_id = ? AND session_id = ?";
    $stmt = mysqli_prepare($conn, $check_cart);
    mysqli_stmt_bind_param($stmt, "is", $product_id, $session_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        // Update quantity
        $row = mysqli_fetch_assoc($result);
        $new_quantity = $row['quantity'] + $quantity;
        $update_query = "UPDATE cart SET quantity = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "ii", $new_quantity, $row['id']);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Cart updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update cart']);
        }
    } else {
        // Add new item
        $insert_query = "INSERT INTO cart (product_id, session_id, quantity) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "isi", $product_id, $session_id, $quantity);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Added to cart']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add to cart']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

mysqli_close($conn);
?>
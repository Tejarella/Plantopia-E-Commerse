<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);
    $session_id = $_SESSION['session_id'];
    
    if ($quantity < 1) {
        echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
        exit;
    }
    
    // Update quantity
    $query = "UPDATE cart SET quantity = ? WHERE id = ? AND session_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iis", $quantity, $cart_id, $session_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Quantity updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update quantity']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

mysqli_close($conn);
?>
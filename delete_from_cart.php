<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_id = intval($_POST['cart_id']);
    $session_id = $_SESSION['session_id'];
    
    // Delete item from cart
    $query = "DELETE FROM cart WHERE id = ? AND session_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "is", $cart_id, $session_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Check if cart is empty
        $count_query = "SELECT COUNT(*) as count FROM cart WHERE session_id = ?";
        $stmt = mysqli_prepare($conn, $count_query);
        mysqli_stmt_bind_param($stmt, "s", $session_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        $cart_empty = ($row['count'] == 0);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Item removed',
            'cart_empty' => $cart_empty
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove item']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

mysqli_close($conn);
?>
<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customerName = mysqli_real_escape_string($conn, $_POST['customerName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $deliveryDate = $_POST['deliveryDate'];
    $timeSlot = $_POST['timeSlot'] ?? '';
    $instructions = mysqli_real_escape_string($conn, $_POST['instructions']);
    $session_id = $_SESSION['session_id'];
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        $total_price = 0;
        $order_items = [];
        
        if ($product_id) {
            // Single product order
            $query = "SELECT * FROM products WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $product_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $product = mysqli_fetch_assoc($result);
            
            if ($product) {
                $total_price = $product['price'];
                $order_items[] = [
                    'product_id' => $product['id'],
                    'quantity' => 1,
                    'price' => $product['price']
                ];
            }
        } else {
            // Cart order
            $query = "SELECT c.quantity, p.id, p.price FROM cart c 
                      JOIN products p ON c.product_id = p.id 
                      WHERE c.session_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $session_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            while ($row = mysqli_fetch_assoc($result)) {
                $total_price += $row['price'] * $row['quantity'];
                $order_items[] = [
                    'product_id' => $row['id'],
                    'quantity' => $row['quantity'],
                    'price' => $row['price']
                ];
            }
        }
        
        if (empty($order_items)) {
            throw new Exception("No items to order");
        }
        
        // Insert order
        $order_query = "INSERT INTO orders (customer_name, email, phone, address, total_price, session_id, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $order_query);
        mysqli_stmt_bind_param($stmt, "ssssds", $customerName, $email, $phone, $address, $total_price, $session_id);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to create order");
        }
        
        $order_id = mysqli_insert_id($conn);
        
        // Insert order items
        foreach ($order_items as $item) {
            $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $item_query);
            mysqli_stmt_bind_param($stmt, "iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to add order item");
            }
        }
        
        // Clear cart if it was a cart order
        if (!$product_id) {
            $clear_cart = "DELETE FROM cart WHERE session_id = ?";
            $stmt = mysqli_prepare($conn, $clear_cart);
            mysqli_stmt_bind_param($stmt, "s", $session_id);
            mysqli_stmt_execute($stmt);
        }
        
        // Commit transaction
        mysqli_commit($conn);
        
        // Redirect to confirmation page with order details
        $redirect_url = "confirmation.php?" . http_build_query([
            'order_id' => $order_id,
            'customerName' => $customerName,
            'email' => $email,
            'phone' => $phone,
            'total' => $total_price,
            'deliveryDate' => $deliveryDate,
            'timeSlot' => $timeSlot
        ]);
        
        header("Location: " . $redirect_url);
        exit;
        
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: indexp.php");
    exit;
}

mysqli_close($conn);
?>
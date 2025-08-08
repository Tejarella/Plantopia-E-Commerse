<?php
require_once 'config.php';

$session_id = $_SESSION['session_id'];

// Fetch orders for this session
$query = "SELECT o.*, COUNT(oi.id) as item_count 
          FROM orders o 
          LEFT JOIN order_items oi ON o.id = oi.order_id 
          WHERE o.session_id = ? 
          GROUP BY o.id 
          ORDER BY o.created_at DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $session_id);
mysqli_stmt_execute($stmt);
$orders = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - Plantopia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f9ff 100%);
            padding-top: 70px;
            min-height: 100vh;
        }
        .navbar ul li a:hover {
            color: #187136;
        }
        .history-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .order-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .order-card:hover {
            transform: translateY(-5px);
        }
        .order-header {
            background: linear-gradient(135deg, #28a745, #20c948);
            color: white;
            padding: 20px;
        }
        .order-body {
            padding: 25px;
        }
        .order-status {
            background: #d4edda;
            color: #155724;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .order-items {
            margin-top: 20px;
        }
        .order-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .empty-history {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .empty-history i {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 20px;
        }
        .page-title {
            color: #2e7d32;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }
        .btn-view-details {
            background: #17a2b8;
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .btn-view-details:hover {
            background: #138496;
            color: white;
        }
        .order-summary {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top"style="height:70px; padding: 8px 20px;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="logo.png" alt="Plantopia" width="140" height="100">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="indexp.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bag.php">Bag</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="history.php">Orders</a>
                    </li>
                </ul>
                <a href="indexp.php" class="btn btn-outline-light">
                    <i class="fas fa-leaf me-2"></i>Shop More Plants
                </a>
            </div>
        </div>
    </nav>

    <div class="history-container">
        <h1 class="page-title">
            <i class="fas fa-clipboard-list me-3"></i>Your Order History
        </h1>

        <?php if (mysqli_num_rows($orders) == 0): ?>
            <div class="empty-history">
                <i class="fas fa-seedling"></i>
                <h3>No Orders Yet</h3>
                <p class="text-muted mb-4">You haven't placed any orders yet. Start building your plant collection!</p>
                <a href="indexp.php" class="btn btn-success btn-lg">
                    <i class="fas fa-leaf me-2"></i>Shop Plants Now
                </a>
            </div>
        <?php else: ?>
            <?php while ($order = mysqli_fetch_assoc($orders)): ?>
                <?php
                // Get order items
                $item_query = "SELECT oi.quantity, oi.price, p.name, p.image 
                               FROM order_items oi 
                               JOIN products p ON oi.product_id = p.id 
                               WHERE oi.order_id = ?";
                $stmt = mysqli_prepare($conn, $item_query);
                mysqli_stmt_bind_param($stmt, "i", $order['id']);
                mysqli_stmt_execute($stmt);
                $items = mysqli_stmt_get_result($stmt);
                ?>
                
                <div class="order-card">
                    <div class="order-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-1">
                                    <i class="fas fa-receipt me-2"></i>
                                    Order #PLT<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?>
                                </h4>
                                <small>Placed on <?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?></small>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="order-status mb-2">
                                    <i class="fas fa-check-circle me-1"></i>Order Confirmed
                                </div>
                                <h5 class="mb-0">₹<?php echo number_format($order['total_price'], 2); ?></h5>
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-user me-2"></i>Customer Details</h6>
                                <p class="mb-1"><strong><?php echo htmlspecialchars($order['customer_name']); ?></strong></p>
                                <p class="mb-1 text-muted"><?php echo htmlspecialchars($order['email']); ?></p>
                                <p class="mb-0 text-muted"><?php echo htmlspecialchars($order['phone']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-map-marker-alt me-2"></i>Delivery Address</h6>
                                <p class="text-muted mb-0"><?php echo nl2br(htmlspecialchars($order['address'])); ?></p>
                            </div>
                        </div>

                        <div class="order-items">
                            <h6><i class="fas fa-leaf me-2"></i>Items Ordered (<?php echo $order['item_count']; ?>)</h6>
                            
                            <?php while ($item = mysqli_fetch_assoc($items)): ?>
                                <div class="order-item">
                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                         class="rounded me-3" 
                                         style="width: 50px; height: 50px; object-fit: cover;" 
                                         alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                        <small class="text-muted">Quantity: <?php echo $item['quantity']; ?></small>
                                    </div>
                                    <div class="text-success fw-bold">
                                        ₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <div class="order-summary">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between">
                                        <span>Subtotal:</span>
                                        <span>₹<?php echo number_format($order['total_price'], 2); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Delivery:</span>
                                        <span class="text-success">FREE</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total:</span>
                                        <span class="text-success">₹<?php echo number_format($order['total_price'], 2); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="text-muted">
                                        <i class="fas fa-truck me-2"></i>
                                        <small>Estimated Delivery: 2-3 days</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <button class="btn btn-view-details" onclick="toggleDetails(<?php echo $order['id']; ?>)">
                                        <i class="fas fa-eye me-2"></i>View Details
                                    </button>
                                    <a href="indexp.php" class="btn btn-outline-success btn-sm ms-2">
                                        <i class="fas fa-redo me-2"></i>Reorder
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>

        <?php if (mysqli_num_rows($orders) > 0): ?>
            <div class="text-center mt-4">
                <p class="text-muted">
                    <i class="fas fa-info-circle me-2"></i>
                    Need help with your order? Contact us at <strong>care@plantopia.com</strong>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleDetails(orderId) {
            // This could expand to show more detailed tracking information
            alert(`Order #PLT${orderId.toString().padStart(6, '0')} details:\n\nStatus: Order Confirmed\nExpected delivery: 2-3 business days\nTracking updates will be sent via SMS & Email`);
        }
    </script>
</body>
</html>
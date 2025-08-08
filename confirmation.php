<?php
require_once 'config.php';

$order_id = $_GET['order_id'] ?? '';
$customerName = $_GET['customerName'] ?? '';
$email = $_GET['email'] ?? '';
$phone = $_GET['phone'] ?? '';
$total = $_GET['total'] ?? '';
$deliveryDate = $_GET['deliveryDate'] ?? '';
$timeSlot = $_GET['timeSlot'] ?? '';

// Get order details
$order_items = [];
if ($order_id) {
    $query = "SELECT oi.quantity, oi.price, p.name, p.image 
              FROM order_items oi 
              JOIN products p ON oi.product_id = p.id 
              WHERE oi.order_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $order_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - Plantopia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f9ff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 70px;
            min-height: 100vh;
        }
        .navbar ul li a:hover {
            color: #187136;
        }
        .confirmation-container {
            background: white;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 50px;
            margin: 40px auto;
            max-width: 800px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .confirmation-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #28a745, #20c948, #17a2b8);
        }
        .success-icon {
            font-size: 5rem;
            color: #28a745;
            animation: bounce 2s infinite;
            margin-bottom: 30px;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }
        .order-id {
            background: linear-gradient(135deg, #28a745, #20c948);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: bold;
            letter-spacing: 1px;
            display: inline-block;
            margin: 20px 0;
        }
        .delivery-timeline {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
        }
        .timeline-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .timeline-item:hover {
            background: white;
            transform: translateX(5px);
        }
        .timeline-icon {
            width: 40px;
            height: 40px;
            background: #28a745;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
        }
        .order-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
        }
        .product-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .action-buttons {
            margin-top: 40px;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #28a745, #20c948);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            margin: 10px;
            transition: all 0.3s ease;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
        }
        .btn-outline-custom {
            border: 2px solid #28a745;
            color: #28a745;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            margin: 10px;
            transition: all 0.3s ease;
        }
        .btn-outline-custom:hover {
            background: #28a745;
            color: white;
            transform: translateY(-2px);
        }
        .delivery-estimate {
            background: linear-gradient(135deg, #e8f5e8, #f0f9ff);
            border: 2px solid #28a745;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            animation: pulse 3s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
        }
        .contact-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top"style="height:70px; padding: 8px 20px;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="logo.png" alt="Plantopia" width="140" height="60">
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
                        <a class="nav-link" href="history.php">Orders</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="confirmation-container">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1 class="text-success mb-4">🎉 Order Placed Successfully!</h1>
            <p class="lead">Thank you, <strong><?php echo htmlspecialchars($customerName); ?></strong>! Your green friends are on their way.</p>
            
            <div class="order-id">
                Order ID: #PLT<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?>
            </div>

            <div class="delivery-estimate">
                <h4><i class="fas fa-truck text-success me-2"></i>Estimated Delivery</h4>
                <h3 class="text-success mb-0">
                    <?php 
                    if ($deliveryDate) {
                        echo date('D, M j, Y', strtotime($deliveryDate));
                        if ($timeSlot) echo " • $timeSlot";
                    } else {
                        echo date('D, M j, Y', strtotime('+2 days'));
                    }
                    ?>
                </h3>
                <small class="text-muted">We'll send you tracking updates via SMS & Email</small>
            </div>

            <?php if (!empty($order_items)): ?>
            <div class="order-summary">
                <h5><i class="fas fa-receipt me-2"></i>Order Summary</h5>
                <?php foreach ($order_items as $item): ?>
                    <div class="product-item">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                             class="rounded me-3" 
                             style="width: 50px; height: 50px; object-fit: cover;" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div class="flex-grow-1">
                            <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                            <small class="text-muted">Qty: <?php echo $item['quantity']; ?></small>
                        </div>
                        <div class="text-success fw-bold">
                            ₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total Paid:</strong>
                    <strong class="text-success">₹<?php echo number_format($total, 2); ?></strong>
                </div>
            </div>
            <?php endif; ?>

            <div class="delivery-timeline">
                <h5><i class="fas fa-route me-2"></i>Delivery Timeline</h5>
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <strong>Order Confirmed</strong>
                        <div class="text-muted small">Your order has been received and confirmed</div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <div>
                        <strong>Plants Being Prepared</strong>
                        <div class="text-muted small">Our experts are carefully packaging your plants</div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div>
                        <strong>Out for Delivery</strong>
                        <div class="text-muted small">Your plants will be delivered with care</div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div>
                        <strong>Delivered</strong>
                        <div class="text-muted small">Enjoy your new green companions!</div>
                    </div>
                </div>
            </div>

            <div class="contact-info">
                <h6><i class="fas fa-headset me-2"></i>Need Help?</h6>
                <p class="mb-0">Contact our plant care experts at <strong>+91-9876543210</strong> or <strong>care@plantopia.com</strong></p>
            </div>

            <div class="action-buttons">
                <a href="history.php" class="btn btn-success btn-primary-custom">
                    <i class="fas fa-history me-2"></i>View All Orders
                </a>
                <a href="indexp.php" class="btn btn-outline-success btn-outline-custom">
                    <i class="fas fa-leaf me-2"></i>Continue Shopping
                </a>
            </div>

            <div class="mt-4">
                <h6 class="text-success">🌱 Plant Care Tips Included!</h6>
                <p class="text-muted small">Each order comes with detailed care instructions to help your plants thrive.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Celebrate with confetti effect (optional)
        function createConfetti() {
            const colors = ['#28a745', '#20c948', '#17a2b8', '#ffc107'];
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.position = 'fixed';
                    confetti.style.width = '10px';
                    confetti.style.height = '10px';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.left = Math.random() * window.innerWidth + 'px';
                    confetti.style.top = '-10px';
                    confetti.style.pointerEvents = 'none';
                    confetti.style.borderRadius = '50%';
                    confetti.style.zIndex = '9999';
                    document.body.appendChild(confetti);
                    
                    let pos = -10;
                    const fall = setInterval(() => {
                        pos += 5;
                        confetti.style.top = pos + 'px';
                        if (pos > window.innerHeight) {
                            clearInterval(fall);
                            document.body.removeChild(confetti);
                        }
                    }, 50);
                }, i * 100);
            }
        }
        
        // Run confetti on page load
        window.addEventListener('load', createConfetti);
    </script>
</body>
</html>
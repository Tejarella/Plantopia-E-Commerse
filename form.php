<?php
require_once 'config.php';

$session_id = $_SESSION['session_id'];
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;

// Get current user data for auto-fill
$current_user = getCurrentUser();

// Get cart items and total
$cart_items = [];
$cart_total = 0;

if ($product_id) {
    // Single product checkout
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    
    if ($product) {
        $cart_items[] = $product;
        $cart_total = $product['price'];
    }
} else {
    // Cart checkout
    $query = "SELECT c.quantity, p.* FROM cart c 
              JOIN products p ON c.product_id = p.id 
              WHERE c.session_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $session_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $cart_items[] = $row;
        $cart_total += $row['price'] * ($row['quantity'] ?? 1);
    }
}

if (empty($cart_items)) {
    header('Location: indexp.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Plantopia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f9ff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 70px;
        }
        .navbar ul li a:hover {
            color: #187136;
        }
        .checkout-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            margin: 30px 0;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .submit-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c948 100%);
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
        }
        .order-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            position: sticky;
            top: 90px;
        }
        .product-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .section-title {
            color: #2e7d32;
            font-weight: 600;
            margin-bottom: 25px;
            position: relative;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 3px;
            background: #28a745;
            border-radius: 2px;
        }
        .delivery-info {
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f9ff 100%);
            border-left: 4px solid #28a745;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .user-info-badge {
            background: linear-gradient(135deg, #28a745, #20c948);
            color: white;
            padding: 10px 15px;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            display: inline-block;
        }
        .auto-filled {
            background-color: #f8f9fa;
            border-left: 3px solid #28a745 !important;
        }
        .login-prompt {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
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
                </ul>
                <div class="d-flex align-items-center">
                    <?php if (isLoggedIn()): ?>
                        <span class="text-light me-3">
                            <i class="fas fa-user me-2"></i>Welcome, <?php echo htmlspecialchars($current_user['name']); ?>
                        </span>
                        <a href="logout.php" class="btn btn-outline-light btn-sm me-3">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    <?php endif; ?>
                    <span class="text-light me-3">
                        <i class="fas fa-lock me-2"></i>Secure Checkout
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="checkout-container">
                    <?php if (isLoggedIn()): ?>
                        <div class="user-info-badge">
                            <i class="fas fa-check-circle me-2"></i>Logged in as <?php echo htmlspecialchars($current_user['name']); ?>
                        </div>
                    <?php else: ?>
                        <div class="login-prompt">
                            <h6><i class="fas fa-info-circle me-2"></i>Want a faster checkout?</h6>
                            <p class="mb-3">Login to auto-fill your details and track your orders!</p>
                            <a href="login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn btn-warning btn-sm me-2">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                            <a href="register.php" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </div>
                    <?php endif; ?>

                    <h2 class="section-title">
                        <i class="fas fa-shipping-fast me-3"></i>Delivery Details
                    </h2>
                    
                    <form action="place_order.php" method="POST" id="checkoutForm">
                        <?php if ($product_id): ?>
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user me-2"></i>Full Name *
                                    <?php if (isLoggedIn()): ?>
                                        <small class="text-success">✓ Auto-filled</small>
                                    <?php endif; ?>
                                </label>
                                <input type="text" name="customerName" 
                                       class="form-control <?php echo isLoggedIn() ? 'auto-filled' : ''; ?>" 
                                       required 
                                       value="<?php echo isLoggedIn() ? htmlspecialchars($current_user['name']) : ''; ?>"
                                       placeholder="Enter your full name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-phone me-2"></i>Phone Number *
                                    <?php if (isLoggedIn()): ?>
                                        <small class="text-success">✓ Auto-filled</small>
                                    <?php endif; ?>
                                </label>
                                <input type="tel" name="phone" 
                                       class="form-control <?php echo isLoggedIn() ? 'auto-filled' : ''; ?>" 
                                       required
                                       value="<?php echo isLoggedIn() ? htmlspecialchars($current_user['phone']) : ''; ?>"
                                       placeholder="Enter 10-digit mobile number">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email Address *
                                <?php if (isLoggedIn()): ?>
                                    <small class="text-success">✓ Auto-filled</small>
                                <?php endif; ?>
                            </label>
                            <input type="email" name="email" 
                                   class="form-control <?php echo isLoggedIn() ? 'auto-filled' : ''; ?>" 
                                   required
                                   value="<?php echo isLoggedIn() ? htmlspecialchars($current_user['email']) : ''; ?>"
                                   placeholder="Enter your email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt me-2"></i>Delivery Address *
                            </label>
                            <textarea name="address" class="form-control" rows="3" required placeholder="House/Flat No., Street, Landmark, City, State, PIN Code"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-calendar me-2"></i>Preferred Delivery Date
                                </label>
                                <input type="date" name="deliveryDate" class="form-control" 
                                       value="<?php echo date('Y-m-d', strtotime('+2 days')); ?>" 
                                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-clock me-2"></i>Preferred Time Slot
                                </label>
                                <select name="timeSlot" class="form-control">
                                    <option value="9AM-12PM">Morning (9AM - 12PM)</option>
                                    <option value="12PM-4PM">Afternoon (12PM - 4PM)</option>
                                    <option value="4PM-8PM">Evening (4PM - 8PM)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-sticky-note me-2"></i>Special Instructions (Optional)
                            </label>
                            <textarea name="instructions" class="form-control" rows="2" 
                                      placeholder="Any special care instructions or delivery notes..."></textarea>
                        </div>

                        <div class="delivery-info">
                            <h6><i class="fas fa-truck me-2"></i>Delivery Information</h6>
                            <ul class="mb-0">
                                <li>✅ Free delivery on all orders</li>
                                <li>🚚 Delivered within 2-3 business days</li>
                                <li>📦 Secure packaging for plant safety</li>
                                <li>💚 Care instructions included</li>
                            </ul>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success submit-btn">
                                <i class="fas fa-credit-card me-2"></i>Place Order - ₹<?php echo number_format($cart_total, 2); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="order-summary">
                    <h4 class="section-title">Order Summary</h4>
                    
                    <?php foreach ($cart_items as $item): ?>
                        <div class="product-item">
                            <div class="d-flex">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                     class="rounded me-3" 
                                     style="width: 60px; height: 60px; object-fit: cover;" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                    <small class="text-muted">
                                        Qty: <?php echo $item['quantity'] ?? 1; ?>
                                    </small>
                                    <div class="text-success fw-bold">
                                        ₹<?php echo number_format($item['price'] * ($item['quantity'] ?? 1), 2); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₹<?php echo number_format($cart_total, 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Delivery:</span>
                        <span class="text-success">FREE</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Taxes:</span>
                        <span>Included</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong class="text-success">₹<?php echo number_format($cart_total, 2); ?></strong>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded">
                        <h6><i class="fas fa-shield-alt me-2 text-success"></i>Secure Payment</h6>
                        <small class="text-muted">Your payment information is protected with industry-standard encryption.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });

        // Phone number validation
        document.querySelector('input[name="phone"]').addEventListener('input', function() {
            const phoneRegex = /^[6-9]\d{9}$/;
            if (this.value && !phoneRegex.test(this.value)) {
                this.setCustomValidity('Please enter a valid 10-digit mobile number');
            } else {
                this.setCustomValidity('');
            }
        });

        // Auto-filled field animation
        document.addEventListener('DOMContentLoaded', function() {
            const autoFilledFields = document.querySelectorAll('.auto-filled');
            autoFilledFields.forEach(field => {
                field.style.transition = 'all 0.3s ease';
                setTimeout(() => {
                    field.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        field.style.transform = 'scale(1)';
                    }, 200);
                }, 100);
            });
        });
    </script>
</body>
</html>
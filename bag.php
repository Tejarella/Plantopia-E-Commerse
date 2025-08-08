<?php
require_once 'config.php';

$session_id = $_SESSION['session_id'];

// Fetch cart items
$query = "SELECT c.id, c.quantity, p.id as product_id, p.name, p.price, p.image, p.description
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.session_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $session_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$cart_items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
}

$cart_total = getCartTotal($conn, $session_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bag - Plantopia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #e9edeb, #f2fef7);
            padding-top: 70px;
        }
        .navbar ul li a:hover {
            color: #187136;
        }
        .bag-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            margin: 20px 0;
        }
        .cart-item {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .quantity-btn {
            width: 35px;
            height: 35px;
            border: 1px solid #28a745;
            background: white;
            color: #28a745;
        }
        .quantity-btn:hover {
            background: #28a745;
            color: white;
        }
        .quantity-input {
            width: 60px;
            text-align: center;
            border: 1px solid #28a745;
        }
        .delete-btn {
            background: #dc3545;
            border: none;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }
        .delete-btn:hover {
            background: #c82333;
        }
        .checkout-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            position: sticky;
            top: 90px;
        }
        .empty-bag {
            text-align: center;
            padding: 50px 0;
        }
        .empty-bag i {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 20px;
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
                        <a class="nav-link active" href="bag.php">Bag</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="history.php">Orders</a>
                    </li>
                </ul>
                <a href="indexp.php" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="bag-container">
                    <h2 class="mb-4">
                        <i class="fas fa-shopping-bag text-success me-3"></i>Your Plant Collection
                    </h2>
                    
                    <?php if (empty($cart_items)): ?>
                        <div class="empty-bag">
                            <i class="fas fa-seedling"></i>
                            <h3>Your bag is empty</h3>
                            <p class="text-muted">Add some beautiful plants to start growing your collection!</p>
                            <a href="indexp.php" class="btn btn-success btn-lg">
                                <i class="fas fa-leaf me-2"></i>Shop Plants
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($cart_items as $item): ?>
                            <div class="cart-item" id="item-<?php echo $item['id']; ?>">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                             class="img-fluid rounded" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                                             style="height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-4">
                                        <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                                        <p class="text-muted small"><?php echo substr(htmlspecialchars($item['description']), 0, 100); ?>...</p>
                                    </div>
                                    <div class="col-md-2">
                                        <h6 class="text-success">₹<?php echo number_format($item['price'], 2); ?></h6>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] - 1; ?>)">-</button>
                                            <input type="number" class="form-control quantity-input mx-2" 
                                                   value="<?php echo $item['quantity']; ?>" 
                                                   id="qty-<?php echo $item['id']; ?>"
                                                   onchange="updateQuantity(<?php echo $item['id']; ?>, this.value)"
                                                   min="1">
                                            <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] + 1; ?>)">+</button>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button class="delete-btn" onclick="removeFromCart(<?php echo $item['id']; ?>)" title="Remove item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (!empty($cart_items)): ?>
            <div class="col-lg-4">
                <div class="checkout-section">
                    <h4 class="mb-4">Order Summary</h4>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal:</span>
                        <span id="subtotal">₹<?php echo number_format($cart_total, 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Delivery:</span>
                        <span class="text-success">FREE</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total:</strong>
                        <strong id="total" class="text-success">₹<?php echo number_format($cart_total, 2); ?></strong>
                    </div>
                    <div class="d-grid">
                        <a href="form.php" class="btn btn-success btn-lg">
                            <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                        </a>
                    </div>
                    <div class="mt-3 text-center">
                        <small class="text-muted">🚚 Free delivery on all orders • 📦 Arrives in 2-3 days</small>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Success Toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="updateToast" class="toast" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="fas fa-check-circle me-2"></i>
                <strong class="me-auto">Updated!</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                Cart updated successfully!
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update quantity
        function updateQuantity(cartId, newQuantity) {
            if (newQuantity < 1) {
                removeFromCart(cartId);
                return;
            }

            const formData = new FormData();
            formData.append('cart_id', cartId);
            formData.append('quantity', newQuantity);

            fetch('update_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`qty-${cartId}`).value = newQuantity;
                    updateTotals();
                    showToast('Quantity updated!');
                } else {
                    alert('Error updating quantity: ' + data.message);
                }
            });
        }

        // Remove from cart
        function removeFromCart(cartId) {
            if (confirm('Are you sure you want to remove this plant from your bag?')) {
                const formData = new FormData();
                formData.append('cart_id', cartId);

                fetch('delete_from_cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`item-${cartId}`).remove();
                        updateTotals();
                        showToast('Plant removed from bag');
                        
                        // Reload page if cart is empty
                        if (data.cart_empty) {
                            setTimeout(() => location.reload(), 1000);
                        }
                    } else {
                        alert('Error removing item: ' + data.message);
                    }
                });
            }
        }

        // Update totals
        function updateTotals() {
            fetch('get_cart_total.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('subtotal').textContent = `₹${data.total}`;
                    document.getElementById('total').textContent = `₹${data.total}`;
                });
        }

        // Show toast notification
        function showToast(message) {
            document.getElementById('toastMessage').textContent = message;
            const toast = new bootstrap.Toast(document.getElementById('updateToast'));
            toast.show();
        }
    </script>
</body>
</html>
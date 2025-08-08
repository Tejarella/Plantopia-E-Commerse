<?php
require_once 'config.php';

// Check if user is logged in, redirect to login if not
if (!isLoggedIn()) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$current_user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantopia - Shop</title>
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
        .card {
            transition: transform 0.3s ease;
            height: 100%;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .btn-add-to-bag {
            background-color: #28a745;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-add-to-bag:hover {
            background-color: #218838;
            transform: scale(1.05);
        }
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cart-icon {
            position: relative;
            font-size: 1.2rem;
        }
        .price-original {
            color: #28a745;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .price-strike {
            color: #6c757d;
            text-decoration: line-through;
        }
        .user-welcome {
            background: linear-gradient(135deg, #28a745, #20c948);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="height:70px; padding: 8px 20px;">
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
                        <a class="nav-link active" href="indexp.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact Us</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <!-- Welcome message -->
                    <span class="user-welcome me-3">
                        <i class="fas fa-user me-1"></i>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
                    </span>
                    
                    <!-- Cart -->
                    <a href="bag.php" class="btn btn-outline-light me-3">
                        <span class="cart-icon">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="cart-badge" id="cartCount">0</span>
                        </span>
                        Bag
                    </a>
                    
                    <!-- User dropdown -->
                    <div class="dropdown me-2">
                        <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>Account
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="history.php"><i class="fas fa-history me-2"></i>Order History</a></li>
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-edit me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                    
                    <!-- Search -->
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search plants...">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div id="carouselExampleIndicators" class="carousel slide mb-5">
        <div class="carousel-indicators">
           <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
           <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="u.png" class="d-block w-100" alt="Plant Banner 1">
            </div>
            <div class="carousel-item">
                <img src="v.png" class="d-block w-100" alt="Plant Banner 2">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
</button>

<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
</button>
</button>
        </button>
    </div>

    <div class="container">
        <h2 class="text-center mb-4 text-success">🌿 Our Beautiful Plants Collection</h2>
        <div id="productsContainer" class="row">
            <!-- Products will be loaded here -->
        </div>
    </div>

    <!-- Success Toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="addToCartToast" class="toast" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="fas fa-check-circle me-2"></i>
                <strong class="me-auto">Success!</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Plant added to your bag successfully! 🌱
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load products on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
            updateCartCount();
        });

        // Load products from database
        function loadProducts() {
            fetch('fetch_products.php')
                .then(response => response.json())
                .then(products => {
                    const container = document.getElementById('productsContainer');
                    container.innerHTML = '';
                    
                    products.forEach(product => {
                        const productCard = `
                            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <img src="${product.image}" class="card-img-top" style="height: 250px; object-fit: cover;" alt="${product.name}">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">${product.name}</h5>
                                        <p class="card-text flex-grow-1">${product.description}</p>
                                        <div class="mb-3">
                                            <span class="price-original">₹${product.price}</span>
                                            <span class="price-strike ms-2">₹496</span>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button onclick="addToCart(${product.id})" class="btn btn-success btn-add-to-bag">
                                                <i class="fas fa-shopping-bag me-2"></i>ADD TO BAG
                                            </button>
                                            <a href="form.php?product_id=${product.id}" class="btn btn-outline-success">
                                                <i class="fas fa-bolt me-2"></i>BUY NOW
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.innerHTML += productCard;
                    });
                });
        }

        // Add to cart function
        function addToCart(productId) {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', 1);

            fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success toast
                    const toast = new bootstrap.Toast(document.getElementById('addToCartToast'));
                    toast.show();
                    
                    // Update cart count
                    updateCartCount();
                } else {
                    alert('Error adding to cart: ' + data.message);
                }
            });
        }

        // Update cart count
        function updateCartCount() {
            fetch('get_cart_count.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cartCount').textContent = data.count;
                });
        }
    </script>
</body>
</html>  
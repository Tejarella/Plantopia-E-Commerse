<?php
require_once 'config.php';
$current_user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <title>Plantopia - Your Green Paradise</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      background: linear-gradient(to right, #e9edeb, #f2fef7);
      color: #333;
      overflow-x: hidden;
    }
    .navbar ul li a:hover {
      color: #187136;
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
    .hero-banner {
      position: relative;
      height: 50vh;
      background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('first.png') center/cover;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-align: center;
    }
    .hero-content {
      max-width: 800px;
      padding: 40px;
    }
    .hero-title {
      font-size: 3.5rem;
      font-weight: 700;
      margin-bottom: 20px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .hero-subtitle {
      font-size: 1.3rem;
      margin-bottom: 30px;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
    .services-intro {
      max-width: 1200px;
      margin: 0 auto;
      padding: 60px 20px;
      text-align: center;
    }
    .friendly-heading {
      font-size: 2.5rem;
      color: #2a6b4f;
      margin-bottom: 20px;
      font-weight: 600;
    }
    .welcome-message {
      font-size: 1.2rem;
      color: #2c5947;
      max-width: 700px;
      margin: 0 auto 2rem;
      line-height: 1.8;
    }
    .btn-hero {
      background: linear-gradient(135deg, #28a745, #20c948);
      color: white;
      padding: 15px 40px;
      border: none;
      border-radius: 50px;
      font-size: 1.1rem;
      font-weight: 600;
      text-decoration: none;
      display: inline-block;
      margin: 10px;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }
    .btn-hero:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 25px rgba(40, 167, 69, 0.4);
      color: white;
    }
    .section-title {
      text-align: center;
      margin-bottom: 50px;
      font-size: 2.2rem;
      color: #1e4636;
      font-weight: 600;
    }
    .steps-container {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 30px;
      max-width: 1000px;
      margin: auto;
    }
    .step {
      flex: 1;
      min-width: 280px;
      background: white;
      border-radius: 20px;
      padding: 40px 25px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    .step::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #28a745, #20c948);
    }
    .step:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    .step-icon {
      font-size: 3.5rem;
      margin-bottom: 25px;
      display: block;
      color: #28a745;
      transition: all 0.3s ease;
    }
    .step:hover .step-icon {
      transform: scale(1.1);
    }
    .step-title {
      font-size: 1.5rem;
      margin-bottom: 15px;
      color: #2a6b4f;
      font-weight: 600;
    }
    .step-description {
      color: #666;
      font-size: 1rem;
      line-height: 1.7;
    }
    .popular-plants {
      background: white;
      padding: 60px 0;
      margin: 40px 0;
    }
    .card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      transition: all 0.3s ease;
      height: 100%;
    }
    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    .card-img-top {
      height: 220px;
      object-fit: cover;
      transition: all 0.3s ease;
    }
    .card:hover .card-img-top {
      transform: scale(1.05);
    }
    .card-body {
      padding: 20px;
    }
    .card-title {
      color: #2a6b4f;
      font-weight: 600;
      margin-bottom: 10px;
    }
    .features-section {
      background: linear-gradient(135deg, #e8f5e8, #f0f9ff);
      padding: 60px 20px;
      margin: 40px 0;
    }
    .feature-item {
      text-align: center;
      padding: 30px 20px;
    }
    .feature-icon {
      font-size: 3rem;
      color: #28a745;
      margin-bottom: 20px;
    }
    .footer {
      background: #2c5947;
      color: white;
      padding: 40px 0;
      text-align: center;
    }
    .social-links a {
      color: white;
      font-size: 1.5rem;
      margin: 0 15px;
      transition: all 0.3s ease;
    }
    .social-links a:hover {
      color: #28a745;
      transform: translateY(-3px);
    }
    .user-welcome {
      background: linear-gradient(135deg, #28a745, #20c948);
      color: white;
      padding: 8px 15px;
      border-radius: 20px;
      font-size: 0.9rem;
      font-weight: 500;
    }
    .logout-success {
      background: linear-gradient(135deg, #17a2b8, #20c0d8);
      color: white;
      padding: 15px;
      text-align: center;
      margin-bottom: 20px;
      border-radius: 10px;
      animation: slideDown 0.5s ease;
    }
    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
    <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
        <div class="logout-success">
            <i class="fas fa-check-circle me-2"></i>You have been successfully logged out. Thank you for visiting Plantopia!
        </div>
    <?php endif; ?>

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
                <a class="nav-link active" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="indexp.php" onclick="checkLoginAndRedirect('indexp.php')">Shop</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="contact.php">Contact</a>
              </li>
            </ul>
            <div class="d-flex align-items-center">
                <?php if (isLoggedIn()): ?>
                    <!-- Logged in user navigation -->
                    <span class="user-welcome me-3">
                        <i class="fas fa-user me-1"></i>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
                    </span>
                    <a href="bag.php" class="btn btn-outline-light me-2">
                        <span class="cart-icon">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="cart-badge" id="cartCount">0</span>
                        </span>
                        Bag
                    </a>
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
                <?php else: ?>
                    <!-- Guest user navigation -->
                    <a href="login.php" class="btn btn-outline-success me-2">
                      <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                    <a href="register.php" class="btn btn-success me-2">
                      <i class="fas fa-user-plus me-1"></i>Register
                    </a>
                <?php endif; ?>
            </div>
          </div>
        </div>
      </nav>

  <!-- Hero Section -->
  <section class="hero-banner">
    <div class="hero-content">
      <h1 class="hero-title">🌿 Welcome to Plantopia</h1>
      <p class="hero-subtitle">Transform your space with beautiful, healthy plants delivered to your doorstep</p>
      <a href="indexp.php" onclick="checkLoginAndRedirect('indexp.php')" class="btn-hero">
        <i class="fas fa-leaf me-2"></i>Explore Our Collection
      </a>
      <a href="#how-it-works" class="btn-hero" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px);">
        <i class="fas fa-play me-2"></i>How It Works
      </a>
    </div>
  </section>

  <!-- Popular Plants Section -->
  <section class="popular-plants">
    <div class="container">
      <h2 class="section-title">🌱 Our Most Loved Plants</h2>
      <div class="row g-4">
        <div class="col-lg-3 col-md-6">
          <div class="card">
            <img src="1.jpeg" class="card-img-top" alt="Snake Plant">
            <div class="card-body">
              <h5 class="card-title">Snake Plant</h5>
              <p class="card-text">Perfect for beginners - air-purifying and virtually indestructible!</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-success fw-bold">₹299</span>
                <a href="indexp.php" onclick="checkLoginAndRedirect('indexp.php')" class="btn btn-sm btn-outline-success">View</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="card">
            <img src="2.webp" class="card-img-top" alt="Money Plant">
            <div class="card-body">
              <h5 class="card-title">Money Plant</h5>
              <p class="card-text">Symbol of good luck and prosperity, easy to care for.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-success fw-bold">₹249</span>
                <a href="indexp.php" onclick="checkLoginAndRedirect('indexp.php')" class="btn btn-sm btn-outline-success">View</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="card">
            <img src="4.webp" class="card-img-top" alt="Peace Lily">
            <div class="card-body">
              <h5 class="card-title">Peace Lily</h5>
              <p class="card-text">Elegant white blooms and excellent air purification.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-success fw-bold">₹399</span>
                <a href="indexp.php" onclick="checkLoginAndRedirect('indexp.php')" class="btn btn-sm btn-outline-success">View</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="card">
            <img src="3.webp" class="card-img-top" alt="Bonsai Tree">
            <div class="card-body">
              <h5 class="card-title">Bonsai Tree</h5>
              <p class="card-text">A living art piece that brings zen and balance.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-success fw-bold">₹899</span>
                <a href="indexp.php" onclick="checkLoginAndRedirect('indexp.php')" class="btn btn-sm btn-outline-success">View</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="text-center mt-5">
        <a href="indexp.php" onclick="checkLoginAndRedirect('indexp.php')" class="btn-hero">
          <i class="fas fa-seedling me-2"></i>View All Plants
        </a>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section class="how-it-works" id="how-it-works" style="padding: 60px 20px;">
    <h2 class="section-title">🚀 How Plantopia Works</h2>
    <div class="steps-container">
      <div class="step">
        <span class="step-icon"><i class="fas fa-search"></i></span>
        <h3 class="step-title">1. Browse & Discover</h3>
        <p class="step-description">Explore our curated collection of indoor & outdoor plants, each with detailed care instructions and benefits.</p>
      </div>
      <div class="step">
        <span class="step-icon"><i class="fas fa-shopping-cart"></i></span>
        <h3 class="step-title">2. Add to Bag</h3>
        <p class="step-description">Select your favorite plants, add them to your bag, and proceed to our secure checkout process.</p>
      </div>
      <div class="step">
        <span class="step-icon"><i class="fas fa-truck"></i></span>
        <h3 class="step-title">3. Fast Delivery</h3>
        <p class="step-description">Receive healthy, carefully packaged plants at your doorstep within 2-3 business days, completely free!</p>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features-section">
    <div class="container">
      <h2 class="section-title">Why Choose Plantopia?</h2>
      <div class="row">
        <div class="col-md-3 feature-item">
          <div class="feature-icon">
            <i class="fas fa-shipping-fast"></i>
          </div>
          <h5>Free Delivery</h5>
          <p>Free shipping on all orders across India</p>
        </div>
        <div class="col-md-3 feature-item">
          <div class="feature-icon">
            <i class="fas fa-leaf"></i>
          </div>
          <h5>Healthy Plants</h5>
          <p>Hand-picked, nursery-fresh plants guaranteed</p>
        </div>
        <div class="col-md-3 feature-item">
          <div class="feature-icon">
            <i class="fas fa-headset"></i>
          </div>
          <h5>Expert Support</h5>
          <p>24/7 plant care guidance from our experts</p>
        </div>
        <div class="col-md-3 feature-item">
          <div class="feature-icon">
            <i class="fas fa-undo"></i>
          </div>
          <h5>Easy Returns</h5>
          <p>7-day hassle-free return policy</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer" id="contact">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <h5>Plantopia</h5>
          <p>Your trusted partner in creating green, sustainable spaces.</p>
        </div>
        <div class="col-md-4">
          <h5>Quick Links</h5>
          <ul class="list-unstyled">
            <li><a href="indexp.php" onclick="checkLoginAndRedirect('indexp.php')" class="text-light">Shop Plants</a></li>
            <li><a href="bag.php" class="text-light">My Bag</a></li>
            <?php if (isLoggedIn()): ?>
              <li><a href="history.php" class="text-light">Order History</a></li>
            <?php else: ?>
              <li><a href="login.php" class="text-light">Login</a></li>
            <?php endif; ?>
          </ul>
        </div>
        <div class="col-md-4">
          <h5>Contact Us</h5>
          <p><i class="fas fa-phone me-2"></i>+91-9876543210</p>
          <p><i class="fas fa-envelope me-2"></i>care@plantopia.com</p>
          <div class="social-links">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
          </div>
        </div>
      </div>
      <hr class="my-4">
      <p class="text-center">&copy; 2024 Plantopia. All rights reserved. Made with 💚 for plant lovers.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Check if user is logged in before redirecting to shop
    function checkLoginAndRedirect(url) {
      <?php if (isLoggedIn()): ?>
        window.location.href = url;
      <?php else: ?>
        // Show login prompt with better UX
        if (confirm('Please login to access our plant collection and enjoy personalized shopping experience. Would you like to login now?')) {
          window.location.href = 'login.php?redirect=' + encodeURIComponent(url);
        }
      <?php endif; ?>
    }

    // Update cart count on page load
    document.addEventListener('DOMContentLoaded', function() {
      <?php if (isLoggedIn()): ?>
        updateCartCount();
      <?php endif; ?>
      
      // Auto-hide logout success message
      const logoutMessage = document.querySelector('.logout-success');
      if (logoutMessage) {
        setTimeout(() => {
          logoutMessage.style.animation = 'slideUp 0.5s ease forwards';
          setTimeout(() => {
            logoutMessage.remove();
          }, 500);
        }, 3000);
      }
    });

    // Update cart count
    function updateCartCount() {
      fetch('get_cart_count.php')
        .then(response => response.json())
        .then(data => {
          const cartCount = document.getElementById('cartCount');
          if (cartCount) {
            cartCount.textContent = data.count;
          }
        })
        .catch(error => {
          console.log('Error fetching cart count:', error);
        });
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Add slide up animation for logout message
    const style = document.createElement('style');
    style.textContent = `
      @keyframes slideUp {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-20px); }
      }
    `;
    document.head.appendChild(style);
  </script>
</body>
</html>
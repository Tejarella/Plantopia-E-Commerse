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
  <title>Contact Us - Plantopia</title>
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
      padding-top: 90px;
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
    .user-welcome {
      background: linear-gradient(135deg, #28a745, #20c948);
      color: white;
      padding: 8px 15px;
      border-radius: 20px;
      font-size: 0.9rem;
      font-weight: 500;
    }
    .contact-hero {
      background: linear-gradient(135deg, #2a6b4f, #28a745);
      color: white;
      padding: 60px 0;
      text-align: center;
      margin-bottom: 50px;
    }
    .contact-hero h1 {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 20px;
    }
    .contact-hero p {
      font-size: 1.2rem;
      opacity: 0.9;
    }
    .contact-section {
      padding: 60px 0;
    }
    .contact-card {
      background: white;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.1);
      margin-bottom: 30px;
      transition: all 0.3s ease;
      border: none;
    }
    .contact-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    }
    .contact-icon {
      font-size: 3rem;
      color: #28a745;
      margin-bottom: 20px;
    }
    .contact-title {
      font-size: 1.5rem;
      color: #2a6b4f;
      font-weight: 600;
      margin-bottom: 15px;
    }
    .contact-info {
      color: #666;
      font-size: 1.1rem;
      line-height: 1.8;
    }
    .contact-info a {
      color: #28a745;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    .contact-info a:hover {
      color: #1e5e32;
      text-decoration: underline;
    }
    .social-links {
      text-align: center;
      margin-top: 30px;
    }
    .social-link {
      display: inline-block;
      margin: 0 15px;
      padding: 15px;
      background: linear-gradient(135deg, #28a745, #20c948);
      color: white;
      border-radius: 50%;
      font-size: 1.5rem;
      width: 60px;
      height: 60px;
      line-height: 30px;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }
    .social-link:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(40, 167, 69, 0.4);
      color: white;
    }
    .contact-form {
      background: white;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    .form-control {
      border: 2px solid #e9ecef;
      border-radius: 10px;
      padding: 12px 15px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }
    .form-control:focus {
      border-color: #28a745;
      box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
    .btn-submit {
      background: linear-gradient(135deg, #28a745, #20c948);
      color: white;
      padding: 12px 30px;
      border: none;
      border-radius: 10px;
      font-size: 1.1rem;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }
    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(40, 167, 69, 0.4);
    }
    .map-container {
      background: white;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.1);
      margin-top: 30px;
    }
    .map-placeholder {
      background: linear-gradient(135deg, #e8f5e8, #f0f9ff);
      height: 300px;
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #666;
      font-size: 1.1rem;
    }
    .footer {
      background: #2c5947;
      color: white;
      padding: 40px 0;
      text-align: center;
      margin-top: 50px;
    }
    .footer .social-links a {
      color: white;
      font-size: 1.5rem;
      margin: 0 15px;
      transition: all 0.3s ease;
    }
    .footer .social-links a:hover {
      color: #28a745;
      transform: translateY(-3px);
    }
    .business-hours {
      background: linear-gradient(135deg, #28a745, #20c948);
      color: white;
      padding: 30px;
      border-radius: 15px;
      margin-top: 20px;
    }
    .hours-title {
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 15px;
    }
    .hours-list {
      list-style: none;
      padding: 0;
    }
    .hours-list li {
      display: flex;
      justify-content: space-between;
      padding: 5px 0;
      border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    .hours-list li:last-child {
      border-bottom: none;
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
                <a class="nav-link" href="indexp.php" onclick="checkLoginAndRedirect('indexp.php')">Shop</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="contact.php">Contact</a>
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

    <!-- Contact Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <h1><i class="fas fa-leaf me-3"></i>Get In Touch</h1>
            <p>We're here to help you grow your green paradise. Reach out to us anytime!</p>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="contact-section">
        <div class="container">
            <div class="row">
                <!-- Contact Cards -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="contact-card text-center">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h3 class="contact-title">Call Us</h3>
                        <div class="contact-info">
                            <p><strong>Phone:</strong> <a href="tel:+918317580631">+91-8317580631</a></p>
                            <p><strong>Contact Person:</strong> Tejaswi Arella</p>
                            <p class="text-muted">Available 24/7 for plant emergencies!</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="contact-card text-center">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3 class="contact-title">Email Us</h3>
                        <div class="contact-info">
                            <p><strong>General Inquiries:</strong><br>
                            <a href="mailto:care@plantopia.com">care@plantopia.com</a></p>
                            <p><strong>Support:</strong><br>
                            <a href="mailto:support@plantopia.com">support@plantopia.com</a></p>
                            <p><strong>Business:</strong><br>
                            <a href="mailto:tejaswi@plantopia.com">tejaswi@plantopia.com</a></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="contact-card text-center">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3 class="contact-title">Visit Us</h3>
                        <div class="contact-info">
                            <p><strong>Address:</strong><br>
                            Plantopia Green House<br>
                            Banjara Hills, Hyderabad<br>
                            Telangana - 500034, India</p>
                            <p class="text-muted">Open for nursery visits by appointment</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Hours -->
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="business-hours text-center">
                        <h3 class="hours-title"><i class="fas fa-clock me-2"></i>Business Hours</h3>
                        <ul class="hours-list">
                            <li><span>Monday - Friday</span><span>9:00 AM - 7:00 PM</span></li>
                            <li><span>Saturday</span><span>9:00 AM - 6:00 PM</span></li>
                            <li><span>Sunday</span><span>10:00 AM - 5:00 PM</span></li>
                            <li><span>Emergency Plant Care</span><span>24/7 Available</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Social Media Links -->
            <div class="row">
                <div class="col-12">
                    <div class="contact-card">
                        <h3 class="contact-title text-center mb-4">Connect With Us</h3>
                        <div class="social-links">
                            <a href="https://instagram.com/plantopia_official" class="social-link" title="Follow us on Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://linkedin.com/in/tejaswi-arella" class="social-link" title="Connect on LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://facebook.com/plantopia.official" class="social-link" title="Like us on Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/plantopia_green" class="social-link" title="Follow us on Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://youtube.com/c/plantopia" class="social-link" title="Subscribe to our YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="https://wa.me/918317580631" class="social-link" title="WhatsApp us">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                        <p class="text-center mt-3 text-muted">Follow us for daily plant care tips, new arrivals, and green inspiration!</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form and Map -->
            <div class="row mt-5">
                <div class="col-lg-6">
                    <div class="contact-form">
                        <h3 class="contact-title mb-4"><i class="fas fa-paper-plane me-2"></i>Send Us a Message</h3>
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Your Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <select class="form-control" id="subject" required>
                                        <option value="">Select a topic</option>
                                        <option value="plant-care">Plant Care Inquiry</option>
                                        <option value="order-support">Order Support</option>
                                        <option value="bulk-order">Bulk Orders</option>
                                        <option value="partnership">Partnership</option>
                                        <option value="feedback">Feedback</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Your Message</label>
                                <textarea class="form-control" id="message" rows="5" required placeholder="Tell us how we can help you..."></textarea>
                            </div>
                            <button type="submit" class="btn-submit w-100">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="map-container">
                        <h3 class="contact-title mb-4"><i class="fas fa-map-marked-alt me-2"></i>Find Us</h3>
                        <div class="map-placeholder">
                            <div class="text-center">
                                <i class="fas fa-map-marker-alt fa-3x text-success mb-3"></i>
                                <h5>Plantopia Nursery</h5>
                                <p>Banjara Hills, Hyderabad<br>Telangana, India</p>
                                <a href="https://maps.google.com/?q=Banjara+Hills+Hyderabad" target="_blank" class="btn btn-success btn-sm">
                                    <i class="fas fa-external-link-alt me-1"></i>Open in Google Maps
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Plantopia</h5>
                    <p>Your trusted partner in creating green, sustainable spaces.</p>
                    <p><strong>Founder:</strong> Tejaswi Arella</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-light">Home</a></li>
                        <li><a href="indexp.php" class="text-light">Shop Plants</a></li>
                        <li><a href="bag.php" class="text-light">My Bag</a></li>
                        <?php if (isLoggedIn()): ?>
                            <li><a href="history.php" class="text-light">Order History</a></li>
                        <?php else: ?>
                            <li><a href="login.php" class="text-light">Login</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Info</h5>
                    <p><i class="fas fa-phone me-2"></i>+91-8317580631</p>
                    <p><i class="fas fa-envelope me-2"></i>care@plantopia.com</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Hyderabad, Telangana</p>
                    <div class="social-links">
                        <a href="https://facebook.com/plantopia.official"><i class="fab fa-facebook"></i></a>
                        <a href="https://instagram.com/plantopia_official"><i class="fab fa-instagram"></i></a>
                        <a href="https://linkedin.com/in/tejaswi-arella"><i class="fab fa-linkedin"></i></a>
                        <a href="https://wa.me/918317580631"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <p class="text-center">&copy; 2024 Plantopia. All rights reserved. Made with 💚 for plant lovers by Tejaswi Arella.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Check if user is logged in before redirecting to shop
        function checkLoginAndRedirect(url) {
            <?php if (isLoggedIn()): ?>
                window.location.href = url;
            <?php else: ?>
                if (confirm('Please login to access our plant collection. Would you like to login now?')) {
                    window.location.href = 'login.php?redirect=' + encodeURIComponent(url);
                }
            <?php endif; ?>
        }

        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isLoggedIn()): ?>
                updateCartCount();
            <?php endif; ?>
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

        // Handle contact form submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                subject: document.getElementById('subject').value,
                message: document.getElementById('message').value
            };

            // Simple validation
            if (!formData.name || !formData.email || !formData.subject || !formData.message) {
                alert('Please fill in all required fields.');
                return;
            }

            // Simulate form submission
            const submitBtn = document.querySelector('.btn-submit');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
            submitBtn.disabled = true;

            // Simulate API call
            setTimeout(() => {
                alert('Thank you for your message! We\'ll get back to you within 24 hours.');
                document.getElementById('contactForm').reset();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });

        // Add smooth scroll animation for any anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>
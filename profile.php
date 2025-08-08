<?php
require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$current_user = getCurrentUser();
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($name) || empty($email) || empty($phone)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = 'Please enter a valid 10-digit phone number.';
    } else {
        // Check if email is already taken by another user
        $email_check_query = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = mysqli_prepare($conn, $email_check_query);
        mysqli_stmt_bind_param($stmt, "si", $email, $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $error = 'This email address is already registered with another account.';
        } else {
            // If password change is requested
            if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
                if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                    $error = 'Please fill in all password fields to change your password.';
                } elseif ($new_password !== $confirm_password) {
                    $error = 'New passwords do not match.';
                } elseif (strlen($new_password) < 6) {
                    $error = 'New password must be at least 6 characters long.';
                } else {
                    // Verify current password
                    $verify_query = "SELECT password FROM users WHERE id = ?";
                    $stmt = mysqli_prepare($conn, $verify_query);
                    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $user_data = mysqli_fetch_assoc($result);
                    
                    if (!password_verify($current_password, $user_data['password'])) {
                        $error = 'Current password is incorrect.';
                    } else {
                        // Update with new password
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $update_query = "UPDATE users SET name = ?, email = ?, phone = ?, password = ? WHERE id = ?";
                        $stmt = mysqli_prepare($conn, $update_query);
                        mysqli_stmt_bind_param($stmt, "ssssi", $name, $email, $phone, $hashed_password, $_SESSION['user_id']);
                        
                        if (mysqli_stmt_execute($stmt)) {
                            $_SESSION['user_name'] = $name;
                            $_SESSION['user_email'] = $email;
                            $_SESSION['user_phone'] = $phone;
                            $success = 'Profile and password updated successfully!';
                        } else {
                            $error = 'Error updating profile. Please try again.';
                        }
                    }
                }
            } else {
                // Update without password change
                $update_query = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $update_query);
                mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $phone, $_SESSION['user_id']);
                
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_phone'] = $phone;
                    $success = 'Profile updated successfully!';
                } else {
                    $error = 'Error updating profile. Please try again.';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Plantopia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #e9edeb, #f2fef7);
            padding-top: 70px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
        .profile-container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .profile-header {
            background: linear-gradient(135deg, #28a745, #20c948);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 3rem;
        }
        .profile-body {
            padding: 40px;
        }
        .form-label {
            font-weight: 600;
            color: #2e7d32;
            margin-bottom: 8px;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .btn-update {
            background: linear-gradient(135deg, #28a745, #20c948);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
            color: white;
        }
        .password-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-top: 30px;
        }
        .password-section h5 {
            color: #2e7d32;
            margin-bottom: 20px;
        }
        .input-group {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #666;
            cursor: pointer;
            z-index: 10;
        }
        .stats-section {
            background: linear-gradient(135deg, #e8f5e8, #f0f9ff);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
        .stat-item {
            text-align: center;
            padding: 15px;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #28a745;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 20px;
        }
        .breadcrumb-item a {
            color: #28a745;
            text-decoration: none;
        }
        .breadcrumb-item.active {
            color: #666;
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
                        <a class="nav-link" href="indexp.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
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
                            <li><a class="dropdown-item active" href="profile.php"><i class="fas fa-user-edit me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home me-1"></i>Home</a></li>
                <li class="breadcrumb-item active">My Profile</li>
            </ol>
        </nav>

        <div class="profile-container">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h2><?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
                <p class="mb-0">Plant enthusiast since joining Plantopia</p>
            </div>

            <div class="profile-body">
                <!-- User Stats -->
                <div class="stats-section">
                    <div class="row">
                        <div class="col-md-4 stat-item">
                            <div class="stat-number" id="totalOrders">0</div>
                            <div class="stat-label">Total Orders</div>
                        </div>
                        <div class="col-md-4 stat-item">
                            <div class="stat-number" id="totalSpent">₹0</div>
                            <div class="stat-label">Total Spent</div>
                        </div>
                        <div class="col-md-4 stat-item">
                            <div class="stat-number" id="plantsOwned">0</div>
                            <div class="stat-label">Plants Owned</div>
                        </div>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <?php if ($success): ?>
                    <div class="alert alert-success" id="successAlert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <!-- Profile Form -->
                <form method="POST" action="profile.php" id="profileForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-user me-2"></i>Full Name
                            </label>
                            <input type="text" name="name" class="form-control" required 
                                   value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>"
                                   placeholder="Enter your full name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <input type="email" name="email" class="form-control" required 
                                   value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>"
                                   placeholder="Enter your email">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-phone me-2"></i>Phone Number
                        </label>
                        <input type="tel" name="phone" class="form-control" required 
                               value="<?php echo htmlspecialchars($_SESSION['user_phone']); ?>"
                               placeholder="Enter your 10-digit phone number" maxlength="10">
                    </div>

                    <!-- Password Change Section -->
                    <div class="password-section">
                        <h5><i class="fas fa-lock me-2"></i>Change Password</h5>
                        <p class="text-muted mb-3">Leave blank if you don't want to change your password</p>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Current Password</label>
                                <div class="input-group">
                                    <input type="password" name="current_password" class="form-control" 
                                           placeholder="Enter current password" id="currentPassword">
                                    <button type="button" class="password-toggle" onclick="togglePassword('currentPassword', 'currentEye')">
                                        <i class="fas fa-eye" id="currentEye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" name="new_password" class="form-control" 
                                           placeholder="Enter new password" id="newPassword">
                                    <button type="button" class="password-toggle" onclick="togglePassword('newPassword', 'newEye')">
                                        <i class="fas fa-eye" id="newEye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" name="confirm_password" class="form-control" 
                                           placeholder="Confirm new password" id="confirmPassword">
                                    <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword', 'confirmEye')">
                                        <i class="fas fa-eye" id="confirmEye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-update btn-lg">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>

                <!-- Quick Actions -->
                <div class="text-center mt-4">
                    <a href="history.php" class="btn btn-outline-success me-2">
                        <i class="fas fa-history me-2"></i>View Order History
                    </a>
                    <a href="indexp.php" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
            loadUserStats();
            
            // Auto-hide success message
            const successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.opacity = '0';
                    setTimeout(() => {
                        successAlert.remove();
                    }, 300);
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

        // Load user statistics
        function loadUserStats() {
            // This would typically fetch data from a PHP endpoint
            // For now, we'll set some placeholder values
            // You can create a separate PHP file to fetch these stats
            setTimeout(() => {
                document.getElementById('totalOrders').textContent = '5';
                document.getElementById('totalSpent').textContent = '₹2,495';
                document.getElementById('plantsOwned').textContent = '12';
            }, 500);
        }

        // Password visibility toggle
        function togglePassword(fieldId, eyeId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(eyeId);
            
            if (field.type === 'password') {
                field.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }

        // Form validation
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            const name = document.querySelector('input[name="name"]').value.trim();
            const email = document.querySelector('input[name="email"]').value.trim();
            const phone = document.querySelector('input[name="phone"]').value.trim();
            const currentPassword = document.querySelector('input[name="current_password"]').value;
            const newPassword = document.querySelector('input[name="new_password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            
            // Basic validation
            if (!name || !email || !phone) {
                e.preventDefault();
                alert('Please fill in all required fields!');
                return false;
            }
            
            // Phone validation
            if (!/^[0-9]{10}$/.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid 10-digit phone number!');
                return false;
            }
            
            // Password validation if changing password
            if (currentPassword || newPassword || confirmPassword) {
                if (!currentPassword || !newPassword || !confirmPassword) {
                    e.preventDefault();
                    alert('Please fill in all password fields to change your password!');
                    return false;
                }
                
                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    alert('New passwords do not match!');
                    return false;
                }
                
                if (newPassword.length < 6) {
                    e.preventDefault();
                    alert('New password must be at least 6 characters long!');
                    return false;
                }
            }
        });

        // Phone number input restriction
        document.querySelector('input[name="phone"]').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });
    </script>
</body>
</html>
<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (!preg_match('/^[6-9]\d{9}$/', $phone)) {
        $error = 'Please enter a valid 10-digit mobile number.';
    } else {
        // Check if email already exists
        $check_query = "SELECT id FROM users WHERE email = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $error = 'Email already registered. Please use a different email or login.';
        } else {
            // Hash password and insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)";
            $insert_stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($insert_stmt, "ssss", $name, $email, $phone, $hashed_password);
            
            if (mysqli_stmt_execute($insert_stmt)) {
                $success = 'Registration successful! You can now login.';
            } else {
                $error = 'Registration failed. Please try again.';
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
    <title>Register - Plantopia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f9ff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }
        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo-section h2 {
            color: #2e7d32;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .logo-section p {
            color: #666;
            margin-bottom: 0;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .btn-register {
            background: linear-gradient(135deg, #28a745 0%, #20c948 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            width: 100%;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .form-label {
            font-weight: 600;
            color: #2e7d32;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-link a {
            color: #28a745;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
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
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo-section">
            <h2><i class="fas fa-leaf me-2"></i>Plantopia</h2>
            <p>Join our green community</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php" id="registerForm">
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-user me-2"></i>Full Name
                </label>
                <input type="text" name="name" class="form-control" required 
                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                       placeholder="Enter your full name">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-envelope me-2"></i>Email Address
                </label>
                <input type="email" name="email" class="form-control" required 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       placeholder="Enter your email">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-phone me-2"></i>Phone Number
                </label>
                <input type="tel" name="phone" class="form-control" required 
                       value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                       placeholder="Enter 10-digit mobile number" maxlength="10">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-lock me-2"></i>Password
                </label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" required 
                           placeholder="Enter password (min 6 characters)" id="password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="password-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-lock me-2"></i>Confirm Password
                </label>
                <div class="input-group">
                    <input type="password" name="confirm_password" class="form-control" required 
                           placeholder="Confirm your password" id="confirm_password">
                    <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                        <i class="fas fa-eye" id="confirm_password-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-success btn-register">
                <i class="fas fa-user-plus me-2"></i>Create Account
            </button>
        </form>

        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
            <p><a href="index.php"><i class="fas fa-home me-2"></i>Back to Home</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password visibility toggle
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(fieldId + '-eye');
            
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
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            const phone = document.querySelector('input[name="phone"]').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }
            
            if (!/^[6-9]\d{9}$/.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9!');
                return false;
            }
        });

        // Phone number input validation
        document.querySelector('input[name="phone"]').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
    </script>
</body>
</html>
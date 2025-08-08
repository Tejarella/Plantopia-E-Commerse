<?php
require_once 'config.php';

$error = '';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        // Check user credentials
        $query = "SELECT id, name, email, phone, password FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_phone'] = $user['phone'];
                
                // Redirect to intended page or home
                $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'indexp.php';
                header('Location: ' . $redirect);
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Plantopia</title>
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
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 450px;
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
        .btn-login {
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
        .btn-login:hover {
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
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        .register-link a {
            color: #28a745;
            text-decoration: none;
            font-weight: 600;
        }
        .register-link a:hover {
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
        .welcome-back {
            background: linear-gradient(135deg, #e8f5e8, #f0f9ff);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .welcome-back i {
            font-size: 2rem;
            color: #28a745;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <h2><i class="fas fa-leaf me-2"></i>Plantopia</h2>
            <p>Welcome back to your green paradise</p>
        </div>

        <div class="welcome-back">
            <i class="fas fa-seedling"></i>
            <h6>Ready to grow your collection?</h6>
            <small class="text-muted">Login to access your personalized plant shopping experience</small>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>" id="loginForm">
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-envelope me-2"></i>Email Address
                </label>
                <input type="email" name="email" class="form-control" required 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       placeholder="Enter your email">
            </div>

            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-lock me-2"></i>Password
                </label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" required 
                           placeholder="Enter your password" id="password">
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="password-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-success btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </button>
        </form>

        <div class="register-link">
            <p>New to Plantopia? <a href="register.php">Create an account</a></p>
            <p><a href="index.php"><i class="fas fa-home me-2"></i>Back to Home</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password visibility toggle
        function togglePassword() {
            const field = document.getElementById('password');
            const eye = document.getElementById('password-eye');
            
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
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.querySelector('input[name="email"]').value;
            const password = document.querySelector('input[name="password"]').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields!');
                return false;
            }
        });
    </script>
</body>
</html>
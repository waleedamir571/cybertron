<?php
require_once 'backend/config/dbc.php';
require_once 'backend/function/functions.php';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (adminLogin($email, $password)) {
        header('Location: /admin/dashboard.php');
        exit;
    } else {
        $error = 'Invalid email or password';
    }
}

if (isset($_GET['logout'])) {
    adminLogout();
}

if (isAdminLoggedIn()) {
    header('Location: /admin/dashboard.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CybertronLabs</title>
    <style>
        /* Inline minimal modern styling */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .login-container {
            background: white; padding: 2rem; border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%; max-width: 400px;
        }
        .login-header { text-align: center; margin-bottom: 2rem; }
        .login-header h1 { color: #333; margin-bottom: .5rem; }
        .login-header p { color: #666; font-size: .9rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: .5rem; color: #333; font-weight: 500; }
        .form-group input {
            width: 100%; padding: .75rem; border: 2px solid #e1e5e9; border-radius: 5px; font-size: 1rem;
        }
        .form-group input:focus { outline: none; border-color: #667eea; }
        .login-btn {
            width: 100%; padding: .75rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; border: none; border-radius: 5px; font-size: 1rem; font-weight: 500; cursor: pointer;
        }
        .error { background: #fee; color: #c33; padding: .75rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #fcc; }
        .demo-credentials { background: #f8f9fa; padding: 1rem; border-radius: 5px; margin-top: 1rem; font-size: .85rem; }
        .demo-credentials h3 { color: #495057; margin-bottom: .5rem; }
        .demo-credentials p { color: #6c757d; margin: .25rem 0; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Admin Panel</h1>
            <p>CybertronLabs Administration</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>

            <button type="submit" name="login" class="login-btn">Sign In</button>
        </form>

        <div class="demo-credentials">
            <h3>Admin Credentials:</h3>
            <p><strong>Email:</strong> admin@example.com</p>
            <p><strong>Password:</strong> password123</p>
        </div>
    </div>
</body>
</html>
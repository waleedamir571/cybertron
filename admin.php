<?php
session_start();
require_once 'backend/config/dbc.php';
require_once 'backend/function/functions.php';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (adminLogin($email, $password)) {
        header('Location: admin/dashboard.php');
        exit;
    } else {
        $error = 'Invalid email or password';
    }
}

if (isset($_GET['logout'])) {
    adminLogout();
}

if (isAdminLoggedIn()) {
    header('Location: admin/dashboard.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CybertronLabs</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="assets/imgs/template/favicon.svg">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #16161A 0%, #5658BE 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: rgba(30, 30, 35, 0.95);
            backdrop-filter: blur(20px);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(86, 88, 190, 0.3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-header h1 {
            color: #5658BE;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .login-header p {
            color: #cccccc;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-group input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #2a2a2f;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #16161A;
            color: white;
        }

        .form-group input:focus {
            outline: none;
            border-color: #5658BE;
            box-shadow: 0 0 0 3px rgba(86, 88, 190, 0.2);
        }

        .form-group i {
            position: absolute;
            left: 1rem;
            top: 2.2rem;
            color: #5658BE;
            font-size: 1.1rem;
        }

        .login-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #5658BE 0%, #4a4cb8 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(86, 88, 190, 0.4);
        }

        .error-message {
            background: rgba(244, 67, 54, 0.1);
            color: #f44336;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #f44336;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .security-note {
            background: rgba(86, 88, 190, 0.1);
            border: 1px solid rgba(86, 88, 190, 0.3);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #cccccc;
            text-align: center;
        }

        .security-note i {
            color: #5658BE;
            margin-right: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-shield-alt"></i> Admin Panel</h1>
            <p>CybertronLabs Administration</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>

            <button type="submit" name="login" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div class="security-note">
            <i class="fas fa-info-circle"></i>
            Secure admin access - Contact administrator for credentials
        </div>
    </div>
</body>

</html>
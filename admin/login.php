<?php
require_once '../config/db.php';

// If already logged in, go to dashboard
if(isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM admins WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        
        // Debug: Check if password verification works
        if(password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Admin username not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - MediCare</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-login-container { max-width: 400px; margin: 100px auto; }
        .admin-title { text-align: center; color: #1e3c72; margin-bottom: 30px; }
        .admin-badge { background: #dc3545; color: white; padding: 5px 10px; border-radius: 5px; font-size: 12px; display: inline-block; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container admin-login-container">
            <h2 class="admin-title">Admin Panel <span class="admin-badge">Staff Only</span></h2>
            
            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="Enter admin username">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Enter password">
                </div>
                <button type="submit" class="btn-submit">Login to Admin Panel</button>
            </form>
            <p style="text-align: center; margin-top: 1rem;">
                <a href="../index.php">← Back to Patient Portal</a>
            </p>
        </div>
    </div>
</body>
</html>
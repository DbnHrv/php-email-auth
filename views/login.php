<?php
if (isset($_SESSION['user'])) {
    header("Location: index.php?action=home");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Email Authentication</title>
    <link rel="stylesheet" href="<?php echo dirname($_SERVER['PHP_SELF']); ?>/../public/style.css">
</head>
<body>
    <div class="container">
        <div class="auth-card">
            <h2>Login</h2>
            <?php if (isset($message)): ?>
                <div class="message error"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                
                <button type="submit" class="btn">Login</button>
            </form>
            
            <p class="switch-form">
                Don't have an account? <a href="index.php?action=register">Register here</a>
            </p>
        </div>
    </div>
</body>
</html>

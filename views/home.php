<?php
if (!isset($_SESSION['user'])) {
    header("Location: index.php?action=login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Email Authentication</title>
    <link rel="stylesheet" href="<?php echo dirname($_SERVER['PHP_SELF']); ?>/../public/style.css">
</head>
<body>
    <div class="container">
        <div class="home-card">
            <h2>Welcome!</h2>
            
            <div class="user-info">
                <p>You are logged in as:</p>
                <p class="email"><strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong></p>
            </div>
            
            <div class="home-content">
                <h3>Welcome to the Email Authentication System</h3>
                <p>You have successfully verified your email and logged in to your account.</p>
                
                <div class="features">
                    <h4>Account Features:</h4>
                    <ul>
                        <li>Secure email verification</li>
                        <li>Password protection with hashing</li>
                        <li>Account management</li>
                    </ul>
                </div>
            </div>
            
            <a href="index.php?action=logout" class="btn logout-btn">Logout</a>
        </div>
    </div>
</body>
</html>

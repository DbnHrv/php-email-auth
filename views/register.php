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
    <title>Register - Email Authentication</title>
    <link rel="stylesheet" href="<?php echo dirname($_SERVER['PHP_SELF']); ?>/../public/style.css">
</head>
<body>
    <div class="container">
        <div class="auth-card">
            <h2>Register</h2>
            
            <?php if (isset($message)): ?>
                <?php if (is_array($message) && $message['success']): ?>
                    <!-- Success Message -->
                    <div class="message success">
                        ✅ <?php echo htmlspecialchars($message['message']); ?>
                    </div>
                    
                    <div class="dev-note">
                        <strong style="font-size: 16px; display: block; margin-bottom: 15px;">📧 Check Your Gmail Inbox!</strong>
                        
                        <div style="background: #fff3e0; padding: 15px; border-left: 4px solid #ff9800; border-radius: 5px; margin-bottom: 15px;">
                            <p style="margin: 0 0 10px 0; color: #e65100;"><strong>✓ Verification email sent!</strong></p>
                            <p style="margin: 0; color: #bf360c; font-size: 14px;">Check your Gmail inbox for the verification email. It should arrive within 5-10 seconds.</p>
                        </div>
                        
                        <div style="background: #e8f5e9; padding: 15px; border-left: 4px solid #4caf50; border-radius: 5px; margin-bottom: 15px;">
                            <p style="margin: 0 0 8px 0; color: #2e7d32;"><strong>What to do next:</strong></p>
                            <ol style="margin: 0; padding-left: 20px; color: #388e3c; font-size: 14px;">
                                <li>Open your Gmail inbox</li>
                                <li>Find email from <strong><?php echo htmlspecialchars(MAIL_FROM_NAME); ?></strong></li>
                                <li><strong>Click "Verify Email Now"</strong> button (quickest)</li>
                                <li>Or enter verification code on verification page</li>
                            </ol>
                        </div>
                        
                        <p style="text-align: center; margin-top: 20px;">
                            <a href="index.php?action=verify" class="btn" style="display: inline-block; margin: 0;">📤 Go to Verification Page</a>
                        </p>
                        
                        <div style="background: #f3e5f5; padding: 12px; border-radius: 5px; margin-top: 15px; font-size: 13px; color: #4a148c;">
                            <strong>💡 Tip:</strong> If you don't see the email, check your Spam or Promotions folder.
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Error Message -->
                    <div class="message error">
                        ❌ <?php echo htmlspecialchars($message); ?>
                    </div>
                    
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" name="password" id="password" required minlength="6">
                            <small>Password must be at least 6 characters</small>
                        </div>
                        
                        <button type="submit" class="btn">Register</button>
                    </form>
                    
                    <p class="switch-form">
                        Already have an account? <a href="index.php?action=login">Login here</a>
                    </p>
                <?php endif; ?>
            <?php else: ?>
                <!-- Registration Form (Initial Load) -->
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" required minlength="6">
                        <small>Password must be at least 6 characters</small>
                    </div>
                    
                    <button type="submit" class="btn">Register</button>
                </form>
                
                <p class="switch-form">
                    Already have an account? <a href="index.php?action=login">Login here</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

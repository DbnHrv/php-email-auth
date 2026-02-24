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
    <title>Verify Email - Email Authentication</title>
    <link rel="stylesheet" href="<?php echo dirname($_SERVER['PHP_SELF']); ?>/../public/style.css">
</head>
<body>
    <div class="container">
        <div class="auth-card">
            <h2>Verify Your Email</h2>
            
            <?php if (isset($message)): ?>
                <div class="message <?php echo strpos($message, 'successful') !== false || strpos($message, 'already verified') !== false ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
                
                <?php if (strpos($message, 'successful') !== false): ?>
                    <div class="verification-success" style="background: #e8f5e9; padding: 20px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #4caf50;">
                        <h3 style="color: #2e7d32; margin-top: 0;">🎉 Email Verified Successfully!</h3>
                        <p style="color: #388e3c; margin: 10px 0;">Your account is now active and ready to use.</p>
                        
                        <div style="background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #2196F3;">
                            <p style="margin: 0 0 10px 0; color: #1976d2;"><strong>Next Step:</strong></p>
                            <p style="margin: 0; color: #333;">You will be redirected to login in <strong>3 seconds</strong>...</p>
                        </div>
                        
                        <p style="margin-top: 20px;"><a href="index.php?action=login" class="btn" style="display: inline-block;">→ Login Now</a></p>
                    </div>
                <?php elseif (strpos($message, 'already verified') !== false): ?>
                    <div class="verification-success" style="background: #e3f2fd; padding: 20px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #2196F3;">
                        <h3 style="color: #1565c0; margin-top: 0;">✓ Account Already Verified</h3>
                        <p style="color: #1976d2; margin: 10px 0;">Your account was previously verified and is ready to use.</p>
                        
                        <p style="margin-top: 20px;"><a href="index.php?action=login" class="btn" style="display: inline-block;">→ Go to Login</a></p>
                    </div>
                <?php else: ?>
                    <!-- Show form again on error -->
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="code">Enter Verification Code:</label>
                            <input type="text" name="code" id="code" placeholder="e.g., 4f5c8a..." required autofocus style="text-transform: lowercase;">
                            <small>Check your email for the verification code sent by <?php echo htmlspecialchars(MAIL_FROM_NAME); ?></small>
                        </div>
                        
                        <button type="submit" class="btn">Verify Code</button>
                    </form>
                    
                    <div style="background: #fff3e0; padding: 15px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #ff9800; text-align: center;">
                        <p style="margin: 0 0 12px 0; color: #e65100;"><strong>📤 Didn't receive the email?</strong></p>
                        <button type="button" onclick="toggleResendForm()" class="btn" style="background-color: #ff9800; display: inline-block; width: auto;">🔄 Resend Verification Email</button>
                    </div>
                    
                    <!-- Resend Email Form (Hidden by default) -->
                    <div id="resend-form" style="display: none; margin-top: 20px; padding: 20px; background: #fff3e0; border-radius: 8px; border-left: 4px solid #ff9800;">
                        <h3 style="margin-top: 0; color: #e65100;">📤 Resend Verification Email</h3>
                        <p style="color: #bf360c; font-size: 14px;">Enter your email address and we'll send you a new verification code.</p>
                        
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="resend-email">Your Email:</label>
                                <input type="email" name="email" id="resend-email" placeholder="example@gmail.com" required>
                                <small>Enter the email address you registered with</small>
                            </div>
                            
                            <div style="display: flex; gap: 10px;">
                                <button type="submit" class="btn" style="flex: 1;">📤 Resend Code</button>
                                <button type="button" onclick="toggleResendForm()" style="flex: 1; padding: 10px 20px; background-color: #ccc; color: #333; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Cancel</button>
                            </div>
                        </form>
                    </div>
                    
                    <p class="switch-form">
                        Don't have an account? <a href="index.php?action=register">Register here</a>
                    </p>
                    
                    <script>
                        function toggleResendForm() {
                            const form = document.getElementById('resend-form');
                            if (form.style.display === 'none') {
                                form.style.display = 'block';
                                document.getElementById('resend-email').focus();
                            } else {
                                form.style.display = 'none';
                            }
                        }
                    </script>
                <?php endif; ?>
            <?php else: ?>
                <!-- Initial verification form -->
                <div style="background: #f5f5f5; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #667eea;">
                    <p style="margin: 0; color: #555;"><strong>📧 Check Your Email</strong></p>
                    <p style="margin: 8px 0 0 0; color: #777; font-size: 14px;">We sent a verification code to your email. Paste it below to verify your account.</p>
                </div>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="code">Verification Code:</label>
                        <input type="text" name="code" id="code" placeholder="e.g., 4f5c8a..." required autofocus style="text-transform: lowercase;">
                        <small>You'll find this code in the email from <?php echo htmlspecialchars(MAIL_FROM_NAME); ?></small>
                    </div>
                    
                    <button type="submit" class="btn">✓ Verify Account</button>
                </form>
                
                <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #2196F3;">
                    <strong style="display: block; color: #1565c0; margin-bottom: 8px;">Alternative: Click Email Link</strong>
                    <p style="margin: 0; color: #1976d2; font-size: 14px;">You can also click the verification link directly in your email - it's faster!</p>
                </div>
                
                <!-- Resend Button -->
                <div style="background: #fff3e0; padding: 15px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #ff9800; text-align: center;">
                    <p style="margin: 0 0 12px 0; color: #e65100;"><strong>📤 Didn't receive the email?</strong></p>
                    <button type="button" onclick="toggleResendForm()" class="btn" style="background-color: #ff9800; display: inline-block; width: auto;">🔄 Resend Verification Email</button>
                </div>
                
                <!-- Resend Email Form (Hidden by default) -->
                <div id="resend-form" style="display: none; margin-top: 20px; padding: 20px; background: #fff3e0; border-radius: 8px; border-left: 4px solid #ff9800;">
                    <h3 style="margin-top: 0; color: #e65100;">📤 Resend Verification Email</h3>
                    <p style="color: #bf360c; font-size: 14px;">Enter your email address and we'll send you a new verification code.</p>
                    
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="resend-email">Your Email:</label>
                            <input type="email" name="email" id="resend-email" placeholder="example@gmail.com" required>
                            <small>Enter the email address you registered with</small>
                        </div>
                        
                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="btn" style="flex: 1;">📤 Resend Code</button>
                            <button type="button" onclick="toggleResendForm()" style="flex: 1; padding: 10px 20px; background-color: #ccc; color: #333; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Cancel</button>
                        </div>
                    </form>
                </div>
                
                <p class="switch-form">
                    Don't have an account? <a href="index.php?action=register">Register here</a>
                </p>
                
                <script>
                    function toggleResendForm() {
                        const form = document.getElementById('resend-form');
                        if (form.style.display === 'none') {
                            form.style.display = 'block';
                            document.getElementById('resend-email').focus();
                        } else {
                            form.style.display = 'none';
                        }
                    }
                </script>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>


<?php
// Email Configuration for Gmail/SMTP

// IMPORTANT: Configure these settings for your Gmail account

// Gmail SMTP Settings
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'doblonharvey12@gmail.com');  // ← CHANGE THIS
define('MAIL_PASSWORD', 'eqsrpcdheyqpyvvw');      // ← CHANGE THIS (App Password, not regular password)
define('MAIL_FROM', 'doblonharvey12@gmail.com');       // ← CHANGE THIS
define('MAIL_FROM_NAME', 'Email Authentication System');

// Enable/Disable Email Sending
define('SEND_EMAILS', true);  // Set to false to use file logging instead

// Verification Settings
define('VERIFICATION_CODE_LENGTH', 32);
define('VERIFICATION_EXPIRY_HOURS', 24);

// Application Settings
define('APP_NAME', 'Email Authentication System');
define('APP_URL', 'http://localhost/php-email-auth');
define('VERIFY_URL', APP_URL . '/index.php?action=verify&code=');

// Optional: Use different method
// 'phpmailer' = PHPMailer (recommended)
// 'smtp' = Direct SMTP (requires proper server setup)
// 'mail' = PHP mail() function (requires sendmail configured)
define('EMAIL_METHOD', 'phpmailer');

?>

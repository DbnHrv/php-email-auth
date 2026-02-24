<?php
// Email Configuration
define('EMAIL_FROM', 'noreply@emailauth.com');
define('EMAIL_FROM_NAME', 'Email Authentication System');

// Database Configuration (can also be set in config/db.php)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'email_auth_db');

// Application Settings
define('VERIFICATION_LINK_EXPIRY', 24); // Hours
define('PASSWORD_MIN_LENGTH', 6);
define('APP_URL', 'http://localhost/php-email-auth');

// Session Settings
define('SESSION_LIFETIME', 3600); // 1 hour in seconds

// Security Settings
ini_set('session.cookie_httponly', 1); // Prevent JavaScript access to session cookie
ini_set('session.cookie_secure', 0);   // Set to 1 in production with HTTPS
ini_set('session.use_strict_mode', 1); // Strict session mode
?>

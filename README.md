# Email Authentication System

A complete PHP-based email authentication system with email verification, secure login, and user registration.

## Features

- User Registration with Email Verification
- Secure Password Hashing (BCRYPT)
- Email Verification System
- Secure Login
- Session Management
- Responsive Design
- Input Validation

## Requirements

- PHP 7.0+
- MySQL 5.5+
- XAMPP or any local server
- Mail Server Configured (for sending verification emails)

## Installation Steps

### 1. Create Database

Open phpMyAdmin and run the SQL commands from `DATABASE_SETUP.sql`:

```sql
CREATE DATABASE IF NOT EXISTS email_auth_db;
USE email_auth_db;

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    verification_code VARCHAR(255) UNIQUE,
    is_verified TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_verification_code (verification_code)
);
```

### 2. Verify Configuration Files

Update `config/db.php` with your database credentials:
```php
$servername = "localhost";
$username = "root";
$password = "";
$database = "email_auth_db";
```

### 3. Configure Email Settings

The system uses PHP's built-in `mail()` function. Make sure:
- Your server has mail capabilities enabled
- Configure your mail server (sendmail, postfix, etc.)

For testing, you can use MailHog or similar services.

### 4. Access the Application

Navigate to: `http://localhost/php-email-auth/`

## File Structure

```
php-email-auth/
├── config/
│   └── db.php                 # Database configuration
├── controllers/
│   └── AuthController.php     # Authentication controller
├── models/
│   └── User.php              # User model with database operations
├── views/
│   ├── login.php             # Login form
│   ├── register.php          # Registration form
│   ├── verify.php            # Email verification page
│   ├── home.php              # Home page after login
│   └── templates/
│       ├── header.php        # Header template
│       └── footer.php        # Footer template
├── public/
│   └── style.css             # CSS styling
├── index.php                 # Main router
└── DATABASE_SETUP.sql        # Database schema
```

## Usage

### Register
1. Click "Register here" on the login page
2. Enter email and password (min 6 characters)
3. Check your email for verification link
4. Click the verification link
5. Login with your credentials

### Login
1. Enter your email and password
2. Click "Login"
3. You'll be redirected to the home page

### Logout
Click "Logout" button on the home page

## Security Features

- Password hashing using BCRYPT
- Email verification before account activation
- SQL injection prevention using prepared statements
- Session-based authentication
- XSS protection with htmlspecialchars()

## Customization

### Change Email Template
Edit the `sendVerificationEmail()` method in `models/User.php`

### Change Styles
Edit `public/style.css`

### Change Database
Update credentials in `config/db.php`

## Troubleshooting

### Emails Not Sending
- Check if mail() function is enabled in php.ini
- Verify mail server is running
- Check logs in `%XAMPP%\sendmail\sendmail.log`

### Database Connection Error
- Ensure MySQL server is running
- Check database credentials in `config/db.php`
- Verify database and tables exist

### Verification Link Not Working
- Check email for correct link format
- Verify verification_code in database
- Check that user exists in database

## Support

For issues or questions, check the following:
1. Ensure all files are in correct directories
2. Verify database tables are created
3. Check mail server configuration
4. Review error logs in browser console

## Security Notes

⚠️ **Important**: 
- Change `noreply@emailauth.com` to your actual email address
- For production, use environment variables for database credentials
- Implement rate limiting for login attempts
- Add CSRF tokens to forms
- Use HTTPS in production
- Update mail server settings for production environment

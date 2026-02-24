# Email Authentication System - Troubleshooting & Testing Guide

## ⚠️ If "Nothing Happened" During Registration

Follow these steps to diagnose and fix the problem:

### Step 1: Check the Debug Panel
Visit: **http://localhost/php-email-auth/debug.php**

This will show you:
- ✓ Database connection status
- ✓ Users table existence
- ✓ Logs directory status
- ✓ All required files
- ✓ Recent registrations

### Step 2: Use the Test Registration Page  
Visit: **http://localhost/php-email-auth/test-register.php**

This bypasses the normal flow and tests registration directly with full diagnostics.

### Common Issues & Solutions

## Issue 1: Form Submits But No Success Message Appears

**Possible Cause:** Form is reloading with empty fields

**Solution:**
1. Go to: http://localhost/php-email-auth/test-register.php
2. Register a test account there
3. Check if you see the success message with the log link

## Issue 2: "Next Step" Message Shows But Log Link Doesn't Work

**Possible Cause:** Incorrect path to logs.php

**Solution:**
1. Try accessing directly: http://localhost/php-email-auth/logs.php
2. If that works, the registration was successful
3. Copy the verification link from there

## Issue 3: Can't See Logs File at All

**Possible Cause:** Logs directory not created or not writable

**Solution:**
1. Check Windows permissions on: `C:\xampp\htdocs\php-email-auth\`
2. Right-click folder → Properties → Security → Make sure "Full Control" is enabled
3. Try the test page again: http://localhost/php-email-auth/test-register.php

## Issue 4: Database Error "Users Table NOT FOUND"

**Possible Cause:** Database schema not set up

**Solution:**
1. Go to: http://localhost/phpmyadmin/
2. Create database: `email_auth_db`
3. Create table by running the SQL from: `DATABASE_SETUP.sql`

Full SQL:
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

## Complete Manual Testing Flow

### Test 1: Basic Registration
```
1. Go to http://localhost/php-email-auth/
2. Click "Register here"
3. Enter: user1@test.com | password123
4. Click "Register"
```

**Expected Result:**
- Page shows success message with green background
- "Next Step" box appears in blue
- "Click Here to View Your Verification Link" button appears

### Test 2: View Verification Link
```
1. Click the blue "Click Here..." button (opens logs.php)
2. Or go directly to: http://localhost/php-email-auth/logs.php
```

**Expected Result:**
- You see email logs with format:
```
================================================================================
[2026-02-24 12:30:00] Email Sent to: user1@test.com
================================================================================
Subject: Email Verification...

VERIFICATION LINK (Copy and paste in browser):
http://localhost/php-email-auth/index.php?action=verify&code=abc123def456...
```

### Test 3: Verify Email
```
1. Copy the entire verification link
2. Paste into browser URL bar
3. Press Enter
```

**Expected Result:**
- You see success message: "Email verified successfully!"
- There's a link to "Login here"

### Test 4: Login with Verified Account
```
1. Click "Login here" or go to http://localhost/php-email-auth/
2. Enter: user1@test.com | password123
3. Click "Login"
```

**Expected Result:**
- Redirected to home page
- Shows: "Welcome!" and your email address
- "Logout" button appears

## Debugging Tools Available

### 1. Debug Panel
**URL:** http://localhost/php-email-auth/debug.php

Shows:
- PHP version
- Database connection status
- Database table status
- File system checks
- Logs directory & file status
- List of recent registrations
- Test email logging function

### 2. Test Registration Page
**URL:** http://localhost/php-email-auth/test-register.php

Features:
- Direct registration testing
- System status checks
- List of last 3 registrations
- Detailed error messages

### 3. Email Logs Viewer
**URL:** http://localhost/php-email-auth/logs.php

Shows:
- All verification links sent
- Timestamps for each registration
- Option to clear logs
- Full email content

## Step-by-Step Fresh Start

If everything is broken, do this:

### 1. Reset Database
```sql
-- Go to http://localhost/phpmyadmin/
-- Drop the database
DROP DATABASE email_auth_db;

-- Create fresh
CREATE DATABASE email_auth_db;
USE email_auth_db;

-- Copy SQL from DATABASE_SETUP.sql and run it
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

### 2. Clear Logs
```
1. Go to http://localhost/php-email-auth/logs.php
2. Click "Clear Logs" button
3. Confirm
```

OR manually:
- Delete: `C:\xampp\htdocs\php-email-auth\logs\emails.log`

### 3. Test Fresh Registration
- Visit: http://localhost/php-email-auth/test-register.php
- Register with: fresh@test.com | password123
- Check http://localhost/php-email-auth/logs.php for link

## Quick Checklist

Before testing, verify:

- [ ] XAMPP is running (Apache & MySQL)
- [ ] Database `email_auth_db` exists
- [ ] Table `users` exists in database
- [ ] Directory `C:\xampp\htdocs\php-email-auth\logs\` exists (or can be created)
- [ ] Directory has write permissions
- [ ] File `logs.php` exists
- [ ] File `test-register.php` exists
- [ ] File `debug.php` exists

## File Locations Reference

```
C:\xampp\htdocs\php-email-auth\
├── index.php                    (Main application)
├── logs.php                     (View email logs) ← Click link here after register
├── test-register.php            (Direct registration testing)
├── debug.php                    (System diagnostics)
├── logs/
│   └── emails.log              (Email verification links stored here)
├── models/
│   └── User.php                (Registration logic)
├── controllers/
│   └── AuthController.php       (Request handler)
├── views/
│   ├── register.php            (Registration form)
│   ├── login.php               (Login form)
│   ├── verify.php              (Verification page)
│   └── home.php                (Dashboard)
├── config/
│   └── db.php                  (Database connection)
└── public/
    └── style.css               (Styling)
```

## Testing Summary

### For Quick Testing Use:
1. **http://localhost/php-email-auth/test-register.php** - Direct registration
2. **http://localhost/php-email-auth/logs.php** - View links
3. **http://localhost/php-email-auth/debug.php** - Check system status

### For Normal Testing Use:
1. **http://localhost/php-email-auth/** - Register normally
2. Look for blue "Next Step" box with button
3. Click button to view verification link
4. Copy link and verify
5. Login with verified account

## Still Not Working?

If you've checked everything and it still doesn't work:

1. Check Windows File Explorer:
   - Go to: C:\xampp\htdocs\php-email-auth\logs\
   - Does `emails.log` file exist?
   - If yes, open it with Notepad - does it have content?

2. Check phpMyAdmin:
   - Go to: http://localhost/phpmyadmin/
   - Click on `email_auth_db` database
   - Click on `users` table
   - Do you see your registered emails?

3. PHP Errors:
   - Visit: http://localhost/php-email-auth/debug.php
   - Look for RED error messages
   - Take screenshot and provide details

4. Browser Console:
   - Press F12 in browser
   - Click "Console" tab
   - Are there any JavaScript errors?

## Success Indicators

You'll know it's working when:

✅ Registration page shows green success message
✅ Blue "Next Step" box appears below success message  
✅ "Click Here to View Your Verification Link" button appears
✅ Clicking button opens logs.php with your email link
✅ Verification link works and marks account as verified
✅ Can login with verified account
✅ Home page displays welcome message

## Support Resources

- `README.md` - Full documentation
- `QUICKSTART.md` - Quick start guide
- `EMAIL_LOGGING.md` - Email logging details
- `DATABASE_SETUP.sql` - Database schema
- `debug.php` - System diagnostics
- `test-register.php` - Test registration

---

**Need Help?** Check the debug.php page first - it will tell you exactly what's wrong! 🚀

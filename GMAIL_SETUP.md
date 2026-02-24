# 📧 Gmail Setup Guide for Email Verification System

This guide explains how to configure Gmail to send verification emails from your PHP application.

## ⚡ Quick Setup (5 minutes)

### Step 1: Enable 2-Factor Authentication (Required for Gmail App Password)

1. Go to: https://myaccount.google.com/security
2. Look for **"2-Step Verification"** on the left panel
3. Click **"Get Started"**
4. Follow the prompts to enable 2FA
   - You'll need to provide a phone number
   - Google will send a verification code
   - Confirm the code

### Step 2: Create an App Password

1. After 2FA is enabled, go back to: https://myaccount.google.com/security
2. Look for **"App passwords"** (it appears only after 2FA is enabled)
3. Select **"Mail"** and **"Windows Computer"** (or your device)
4. Google will generate a **16-character password**
5. **Copy this password** - you'll need it in the next step

### Step 3: Update `config/email.php`

1. Open file: `config/email.php`
2. Find these lines:
   ```php
   define('MAIL_USERNAME', 'your-email@gmail.com');  // ← CHANGE THIS
   define('MAIL_PASSWORD', 'your-app-password');      // ← CHANGE THIS (App Password, not regular password)
   define('MAIL_FROM', 'your-email@gmail.com');       // ← CHANGE THIS
   ```

3. Replace with your actual Gmail settings:
   ```php
   define('MAIL_USERNAME', 'myemail@gmail.com');
   define('MAIL_PASSWORD', 'abcd efgh ijkl mnop');  // 16-character App Password (with spaces)
   define('MAIL_FROM', 'myemail@gmail.com');
   ```

### Step 4: Test the Configuration

1. Open: `http://localhost/php-email-auth/test-register.php`
2. Enter:
   - **Email:** Your Gmail address (e.g., `test@gmail.com`)
   - **Password:** Any password (e.g., `password123`)
3. Click **"Test Registration"**
4. **Check your Gmail inbox** for the verification email
5. You should see it within **5-10 seconds**

---

## 🔧 Configuration Options in `config/email.php`

### Email Method (Choose One)

```php
// Option 1: PHPMailer (Recommended - Most reliable)
define('EMAIL_METHOD', 'phpmailer');

// Option 2: PHP mail() function (Requires sendmail configuration)
define('EMAIL_METHOD', 'mail');
```

### Enable/Disable Sending

```php
// Send actual emails
define('SEND_EMAILS', true);

// Disable sending (useful for testing without Gmail)
define('SEND_EMAILS', false);  // Emails will only be logged to file
```

### Modify Email Details

```php
define('MAIL_FROM_NAME', 'Your Company Name');
define('APP_NAME', 'Your Application Name');
define('APP_URL', 'http://yoursite.com');
```

---

## 📋 Complete Setup Checklist

- [ ] Gmail account created
- [ ] 2-Factor Authentication enabled
- [ ] App Password generated (16 characters)
- [ ] `config/email.php` updated with credentials
- [ ] Tested registration at `test-register.php`
- [ ] Verified email arrives in Gmail inbox within 5-10 seconds
- [ ] Clicked verification link in email
- [ ] Successfully logged in to homepage

---

## 🐛 Troubleshooting

### Email Not Arriving

**Problem:** Verification email not showing in inbox

**Solutions:**
1. Check **Spam/Promotions folder** in Gmail
2. Verify App Password is correct (it's **case-sensitive**)
3. Confirm **2-Factor Authentication is enabled**
4. Check error logs:
   - Open: `debug.php`
   - Look for error messages in "Recent Registrations"
5. Test sending with: `test-register.php`

### "SMTP Connect Failed" Error

**Problem:** System can't connect to Gmail SMTP server

**Causes & Solutions:**
1. **Wrong credentials**: App Password is most common cause
   - Use the **16-character App Password**, not regular password
   - Copy-paste from Gmail (avoids typos)

2. **2FA not enabled**: App passwords only work with 2FA
   - Go to: https://myaccount.google.com/security
   - Enable 2-Step Verification first

3. **App Password not generated**: 
   - Go to: https://myaccount.google.com/security
   - "App passwords" option only shows after 2FA is enabled

4. **Firewall/Port blocked**: 
   - Ensure port 587 is open (SMTP port for Gmail)
   - Some ISPs block SMTP - try port 465 instead in `config/email.php`

### Verification Link Shows in Email But Says "Invalid Code"

**Problem:** Click link but get "Invalid verification code" error

**Causes:**
1. Link didn't properly include the code
2. Code from database doesn't match
3. User already verified (code deleted from DB)

**Solution:**
1. View logs: `http://localhost/php-email-auth/logs.php`
2. Check code shown in logs matches link
3. Try manual code entry instead of link

### Account Shows as "Unverified" After Clicking Link

**Problem:** Email says verified but can't login

**Solutions:**
1. Go to: `http://localhost/php-email-auth/debug.php`
2. Check "Recent Registrations" table
3. Manually verify via `test-register.php`:
   - Get verification code from logs.php
   - Go to: `http://localhost/php-email-auth/index.php?action=verify`
   - Paste code
   - Click Verify

---

## 📱 Complete User Flow

```
User Registration
        ↓
Registration Form (register.php)
        ↓
Email Sent to Gmail (via sendVerificationEmail)
        ↓
User Checks Email Inbox
        ↓
User Sees Verification Code + Link
        ↓
Option A: Click Link (Auto-Verifies)  OR  Option B: Enter Code Manually
        ↓
Account Marked as Verified in Database
        ↓
User Logs In
        ↓
Homepage Access ✅
```

---

## 🔐 Security Notes

### App Passwords vs Regular Passwords

- **Regular Gmail Password**: Your actual account password (DO NOT USE)
- **App Password**: Special 16-character password created for this app only
  - Can be revoked without affecting main account
  - More secure for applications
  - Required when 2FA is enabled

### Best Practices

1. **Use App Password, not regular password**
   - Never paste your actual Gmail password in code
   
2. **Keep credentials in `config/email.php`**
   - Never commit credentials to version control
   - Use `.gitignore` for config files in production

3. **Enable 2-Factor Authentication**
   - Protects your Gmail account
   - Required for App Passwords

4. **Test with `test-register.php` first**
   - Isolated testing environment
   - Easier debugging

5. **Monitor error logs**
   - Check `debug.php` regularly
   - Review errors in PHP error_log

---

## 📝 Environment Variables (Optional for Production)

Instead of hardcoding credentials, use environment variables:

```php
define('MAIL_USERNAME', getenv('MAIL_USERNAME') ?: 'your-email@gmail.com');
define('MAIL_PASSWORD', getenv('MAIL_PASSWORD') ?: 'your-app-password');
```

Then set via `.env` file or server configuration.

---

## ✅ Verification Complete

Once you see:
1. ✅ Registration succeeds
2. ✅ Email arrives in Gmail inbox (5-10 seconds)
3. ✅ Verification link works
4. ✅ Can login after verification

You're all set! The email verification system is working correctly with real Gmail.

---

## 📞 Need Help?

1. Check **error logs**: `debug.php`
2. View **verification codes**: `logs.php`
3. Test **registration**: `test-register.php`
4. See **system health**: `debug.php`

Contact Gmail support if you have account-related issues: https://support.google.com/mail/

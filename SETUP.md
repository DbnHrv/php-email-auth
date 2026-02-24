# 🚀 SETUP INSTRUCTIONS (Start Here!)

## Step 1: Configure Gmail (5 minutes)

### 1.1 Enable 2-Factor Authentication
1. Go to: https://myaccount.google.com/security
2. Find "2-Step Verification"
3. Click "Get Started"
4. Follow the prompts (needs phone number)

### 1.2 Create App Password
1. Go to: https://myaccount.google.com/security
2. Find "App passwords" (appears after 2FA is enabled)
3. Select "Mail" and "Windows Computer"
4. Google will generate a **16-character password**
5. **Copy this password** (you'll need it next)

### 1.3 Update Configuration File

**File:** `config/email.php`

Find these lines:
```php
define('MAIL_USERNAME', 'your-email@gmail.com');  // ← UPDATE THIS
define('MAIL_PASSWORD', 'your-app-password');      // ← UPDATE THIS
define('MAIL_FROM', 'your-email@gmail.com');       // ← UPDATE THIS
```

**Replace with your details:**
```php
define('MAIL_USERNAME', 'myemail@gmail.com');
define('MAIL_PASSWORD', 'abcd efgh ijkl mnop');  // 16-char App Password
define('MAIL_FROM', 'myemail@gmail.com');
```

---

## Step 2: Test the System

### Test via Web Interface
1. Go to: `http://localhost/php-email-auth/test-register.php`
2. Email: Your Gmail address (e.g., `test@gmail.com`)
3. Password: Any password
4. Click "Test Registration"
5. **Check your Gmail inbox** (wait 5-10 seconds)
6. **You should see the verification email!**

---

## Step 3: Complete User Flow

### Register
1. Go to: `http://localhost/php-email-auth/`
2. Register with your Gmail
3. See success message

### Verify
**Option A (Fastest):** Click link in email
**Option B (Alternative):** Enter code on verification page

### Login
After verification, login with your credentials

### Homepage
You'll see your email and "Logged in" message ✅

---

## 🎯 Quick Checklist

- [ ] 2FA enabled on Gmail
- [ ] App Password created & copied
- [ ] `config/email.php` updated
- [ ] Test registration completed
- [ ] Email received in Gmail
- [ ] Full workflow tested (register → verify → login)

---

## ⚠️ Common Issues

| Issue | Solution |
|-------|----------|
| "SMTP Connect Failed" | Your App Password is wrong or 2FA not enabled |
| Email doesn't arrive | Check spam folder + check `debug.php` for errors |
| Code is invalid | Code expires in 24 hours - register again or check `logs.php` |

---

## 📚 Full Documentation

- **GMAIL_SETUP.md** - Detailed Gmail configuration guide
- **COMPLETE_FLOW.md** - Full user workflow explanation
- **debug.php** - System diagnostics dashboard
- **logs.php** - View all verification codes and emails
- **test-register.php** - Isolated testing interface

---

## ✅ You're All Set!

Once you see the verification email in your Gmail inbox, the system is working perfectly. 

**Start here:**
1. Go to `http://localhost/php-email-auth/`
2. Click "Register"
3. Check your Gmail!

Enjoy! 🎉

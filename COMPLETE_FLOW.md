# ⚡ Complete Email Verification Flow Guide

This guide shows you the complete workflow for user registration, email verification, and login.

---

## 🎯 Perfect Flow (Step by Step)

### Step 1: User Registers
**URL:** `http://localhost/php-email-auth/`

1. Click **"Register"** link
2. Enter your **Gmail address** (e.g., `yourname@gmail.com`)
3. Enter **password** (min 6 characters)
4. Click **"Register"** button

**Expected Result:**
- ✅ Green success message appears
- ✅ Shows "Check Your Gmail Inbox" instructions
- ✅ Button: "Go to Verification Page"

---

### Step 2: Check Verification Email
**Time:** Email arrives in 5-10 seconds

1. Open your **Gmail account**
2. Check **Inbox** (or Spam/Promotions folder)
3. Find email from: **`<?php echo MAIL_FROM_NAME; ?>`**

**Email Contents:**
- **Verification Code** displayed prominently (32 characters)
- **"Verify Email Now"** button
- Alternative: Manual code entry instructions

---

### Step 3A: Verify via Email Link (Fastest) ⭐
**Recommended Method**

1. In your Gmail email, click **"Verify Email Now"** button
2. You'll be redirected to: `http://localhost/php-email-auth/index.php?action=verify&code=[CODE]`
3. Page shows: ✅ "Email Verified Successfully!"
4. Auto-redirects to login after 3 seconds

---

### Step 3B: Verify via Code Entry
**Alternative Method** (if email link doesn't work)

1. Go to: `http://localhost/php-email-auth/index.php?action=verify`
2. Find **verification code** in your email
3. Copy and paste the code
4. Click **"✓ Verify Account"**
5. Page shows: ✅ "Email Verified Successfully!"
6. Click **"→ Login Now"** button

---

### Step 4: Login with Verified Account
**URL:** `http://localhost/php-email-auth/index.php?action=login`

1. Enter your **email address**
2. Enter your **password**
3. Click **"Login"** button

**What Happens:**
- ✅ Password is verified
- ✅ Checks if email is verified (if not verified, will show error)
- ✅ Session is created with user info
- ✅ Redirect to homepage

---

### Step 5: Access Homepage
**URL:** `http://localhost/php-email-auth/index.php?action=home`

**You'll see:**
- ✅ Welcome message with your email
- ✅ "You are logged in" confirmation
- ✅ Logout button
- ✅ Feature list

---

## 🔧 Setup Checklist

Before testing, complete these steps:

### Gmail Configuration

- [ ] Go to https://myaccount.google.com/security
- [ ] Enable **2-Step Verification** (2FA)
- [ ] Generate an **App Password** (select Mail + Windows)
- [ ] Copy the **16-character password** (with spaces)
- [ ] Open `config/email.php`
- [ ] Update `MAIL_USERNAME` with your Gmail address
- [ ] Update `MAIL_PASSWORD` with the App Password
- [ ] Update `MAIL_FROM` with your Gmail address

### Test the Setup

1. Go to: `http://localhost/php-email-auth/test-register.php`
2. Register a test account
3. Check Gmail inbox for the email
4. If email arrives → ✅ Setup is working!

---

## 📊 Complete User Journey Diagram

```
START
  ↓
┌─────────────────────────────────────┐
│ 1. User Registration                │
│ • Enter email & password            │
│ • Click "Register"                  │
└─────────────────────────────────────┘
  ↓
┌─────────────────────────────────────┐
│ 2. System Sends Email               │
│ • Generates verification code       │
│ • Sends via Gmail SMTP              │
│ • Takes 5-10 seconds                │
└─────────────────────────────────────┘
  ↓
┌─────────────────────────────────────┐
│ 3. User Receives Email              │
│ • Email in Gmail inbox              │
│ • Shows verification code           │
│ • Shows "Verify Now" link           │
└─────────────────────────────────────┘
  ↓
      ┌──────────────────┬──────────────────┐
      ↓                  ↓
  ┌─────────────────┐  ┌─────────────────┐
  │ Option A        │  │ Option B        │
  │ Click Link      │  │ Enter Code      │
  │ (Automatic)     │  │ (Manual)        │
  └─────────────────┘  └─────────────────┘
      ↓                  ↓
      └──────────────────┴──────────────────┘
         ↓
┌─────────────────────────────────────┐
│ 4. Account Verified                 │
│ • Code marked in database           │
│ • is_verified = 1                   │
│ • User redirected to login          │
└─────────────────────────────────────┘
  ↓
┌─────────────────────────────────────┐
│ 5. User Logs In                     │
│ • Enter email & password            │
│ • System checks verification status │
│ • Creates user session              │
└─────────────────────────────────────┘
  ↓
┌─────────────────────────────────────┐
│ 6. Homepage Access ✅               │
│ • User dashboard                    │
│ • Logout option                     │
│ • User profile (email)              │
└─────────────────────────────────────┘
```

---

## ✅ How to Verify Everything is Working

### Test 1: Register & Check Email
1. Go to: `http://localhost/php-email-auth/`
2. Register with: `test@gmail.com` | `password123`
3. Check Gmail inbox for email
4. ✅ **Expected:** Email arrives in 5-10 seconds

### Test 2: Verify via Link
1. Click link in Gmail email
2. Wait for redirect to login page
3. ✅ **Expected:** "Email Verified Successfully!" message

### Test 3: Login After Verification
1. Go to: `http://localhost/php-email-auth/index.php?action=login`
2. Enter: `test@gmail.com` | `password123`
3. Click "Login"
4. ✅ **Expected:** Redirect to homepage with welcome message

### Test 4: Verify Code Entry Alternative
1. Go to: `http://localhost/php-email-auth/` (register new email)
2. Register with: `test2@gmail.com` | `password456`
3. Go to: `http://localhost/php-email-auth/index.php?action=verify`
4. Get code from email
5. Enter code manually
6. ✅ **Expected:** "Email Verified Successfully!" message
7. Login with verified account
8. ✅ **Expected:** Access homepage

---

## 🐛 Troubleshooting

| Problem | Solution |
|---------|----------|
| **Email not arriving** | Check Gmail Spam/Promotions folder + verify config/email.php credentials |
| **"SMTP Connect Failed"** | Ensure 2FA enabled + use App Password (not regular password) |
| **Can't login after verification** | Check is_verified status in database + try verification code method |
| **Verification link says invalid** | Code might be expired (24 hours) - use manual code entry instead |
| **System can't find email.php** | Ensure config/email.php exists + update with Gmail credentials |

---

## 📚 Available Tools

### Production Use
- **Register:** `/index.php?action=register`
- **Verify:** `/index.php?action=verify`
- **Login:** `/index.php?action=login`
- **Home:** `/index.php?action=home`

### Development/Testing
- **Dashboard:** `/dashboard.php` (Central hub)
- **View Logs:** `/logs.php` (See verification codes)
- **Debug Panel:** `/debug.php` (System health check)
- **Test Register:** `/test-register.php` (Isolated testing)

---

## 🎯 Success Criteria Checklist

- [ ] Gmail configured with App Password
- [ ] Email sends successfully (5-10 seconds)
- [ ] Verification code visible in email
- [ ] Link in email works
- [ ] Manual code entry works
- [ ] Account marked as verified
- [ ] User can login
- [ ] Homepage loads with user email
- [ ] Session works correctly
- [ ] Logout clears session

**Once all checked:** ✅ System is fully functional!

---

## 🔐 Important Security Notes

### For Development
- Use test Gmail accounts
- Keep config/email.php in .gitignore
- Don't commit actual credentials

### For Production
- Use environment variables for credentials
- Use verified domain email (if applicable)
- Monitor email sending logs
- Implement rate limiting on registration
- Set verification code expiry (currently 24 hours)

---

## 📞 Quick Help

**Email not working?**
1. Check `debug.php` for errors
2. Verify `config/email.php` has correct Gmail credentials
3. Test with `test-register.php`
4. View logs at `logs.php`

**Account verification failed?**
1. Go to `logs.php` to see verification code
2. Try manual code entry at `index.php?action=verify`
3. Check database (phpMyAdmin) for user status

**Forgot what codes/links are what?**
1. See all sent emails at `logs.php`
2. Copy codes directly from logs panel
3. One-click copy button for convenience

---

**Everything working? Congratulations! 🎉**

Your email verification system is now fully operational with real Gmail SMTP integration!

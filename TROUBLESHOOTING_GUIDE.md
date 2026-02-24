# 🔧 Debugging & Troubleshooting Guide

## 🚨 Common Issues & Solutions

### Issue 1: Email Not Arriving

**Symptoms:** Registered successfully but no email in inbox

**Step 1: Check Spam Folders**
1. Check **Spam** folder in Gmail
2. Check **Promotions** folder
3. If email is there, mark as "Not Spam"

**Step 2: Verify Configuration**
1. Go to: `http://localhost/php-email-auth/debug.php`
2. Look for **System Status** section
3. Check if database is connected
4. Check if logs directory exists

**Step 3: Check Error Logs**
1. Go to: `http://localhost/php-email-auth/test-register.php`
2. Register a test account
3. Check the error message (if any)
4. Common errors:
   - "SMTP Connect Failed" → Wrong credentials
   - "Authentication Failed" → Wrong App Password
   - "Could not send email" → Network issue

**Step 4: View System Logs**
1. Go to: `http://localhost/php-email-auth/logs.php`
2. Look for your registration entry
3. Check if verification code is shown
4. If code shows → Problem is with Gmail credentials

**Solution:**
- Verify `config/email.php` settings
- Re-check App Password from Gmail
- Ensure 2FA is really enabled
- Try port 465 instead of 587 (edit `config/email.php`)

---

### Issue 2: "Invalid Verification Code" Error

**Symptoms:** Click link or enter code, but get "Invalid code" message

**Causes:**

1. **Code already used:**
   - Once you verify, the code is deleted
   - Can't use same code twice
   - Solution: Register again

2. **Code expired (24 hours):**
   - Codes last 24 hours by default
   - After 24 hours, register again
   - Solution: Create new registration

3. **Wrong code:**
   - Typo when copying code
   - Solution: Go to `logs.php`, copy exact code, try again

4. **Code doesn't match database:**
   - Code from email ≠ Code in database
   - Solution: Check `logs.php` and `debug.php` for actual code

**Debugging Steps:**
1. Go to: `http://localhost/php-email-auth/logs.php`
2. Find your registration
3. Copy the exact code shown
4. Go to: `http://localhost/php-email-auth/index.php?action=verify`
5. Paste code carefully (case-sensitive)
6. Click "✓ Verify Account"

---

### Issue 3: SMTP Connection Failed

**Error Message:** "Failed to connect to mailserver at 'smtp.gmail.com' port 587"

**Root Causes:**
1. Wrong Gmail credentials
2. 2FA not enabled
3. App Password not generated
4. Firewall blocking port 587

**Solution Checklist:**

**Check 1: Gmail Credentials**
```
1. Open config/email.php
2. MAIL_USERNAME = your Gmail address
3. MAIL_PASSWORD = 16-char App Password (not regular password)
4. Both are case-sensitive and must match exactly
```

**Check 2: 2-Factor Authentication**
```
1. Go to: https://myaccount.google.com/security
2. Find "2-Step Verification"
3. Should say "Status: ON"
4. If OFF, enable it
```

**Check 3: App Password**
```
1. Go to: https://myaccount.google.com/security
2. Find "App passwords" 
3. Should show your app
4. If not there, generate one:
   - Select "Mail"
   - Select "Windows Computer"
   - Copy the 16-char password
```

**Check 4: Try Different Port**
```php
// Edit config/email.php
// Change from:
define('MAIL_PORT', 587);
// To:
define('MAIL_PORT', 465);
```

Then test again at `test-register.php`

---

### Issue 4: Account Shows as "Unverified" After Clicking Link

**Symptoms:** Clicked email link, saw success message, but can't login

**Problem:** Account wasn't actually marked as verified in database

**Check Database Status:**
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Go to `email_auth_db` → `users` table
3. Find your row
4. Check `is_verified` column:
   - Should be: `1` (verified)
   - If it's: `0` (not verified) → Issue occurred

**Solutions:**

**Option A: Verify Again (Quickest)**
1. Go to: `http://localhost/php-email-auth/logs.php`
2. Find your registration code
3. Copy the code
4. Go to: `http://localhost/php-email-auth/index.php?action=verify`
5. Paste code
6. Click "✓ Verify Account"
7. Try logging in again

**Option B: Check Using Debug Page**
1. Go to: `http://localhost/php-email-auth/debug.php`
2. Scroll to "Recent Registrations"
3. Find your account
4. Check the `is_verified` column

**Option C: Clear and Restart**
1. Go to: `http://localhost/php-email-auth/debug.php`
2. Click "Clear All Registrations" (if available)
3. Register fresh account
4. Complete full flow again

---

### Issue 5: Link in Email Not Working

**Symptoms:** Email arrived, clicked "Verify Email Now" button, but nothing happens

**Cause:** Link is malformed or code wasn't included

**Debugging:**

1. **Check logs.php** for correct link:
   - Go to: `http://localhost/php-email-auth/logs.php`
   - Find your registration
   - Check "VERIFICATION LINK" section
   - It should look like:
     ```
     http://localhost/php-email-auth/index.php?action=verify&code=abcd1234...
     ```

2. **Try link manually:**
   - Copy the link from logs.php
   - Paste in browser address bar
   - Press Enter
   - Should redirect to verify page

3. **If still not working:**
   - The `&code=...` part might be missing
   - Use Option B above (manual code entry)

---

### Issue 6: Can't Login (Even After Verification)

**Symptoms:** Account verified, but login fails

**Possible Reasons:**

1. **Wrong password:**
   - Password is case-sensitive
   - Minimum 6 characters
   - Solution: Try password again carefully

2. **Account not verified:**
   - is_verified = 0 in database
   - Solution: Complete verification again

3. **Email doesn't exist in database:**
   - Registration failed silently
   - Solution: Register again at `register.php`

4. **Session error:**
   - Server issue with sessions
   - Solution: Clear browser cookies, try again

**Debugging Checklist:**

1. Verify account exists:
   - Go to: `http://localhost/php-email-auth/debug.php`
   - Check "Recent Registrations"
   - Your email should be listed

2. Check is_verified status:
   - Same place as above
   - Should show: `1` (for verified)

3. Try password at login:
   - Go to: `http://localhost/php-email-auth/index.php?action=login`
   - Enter email and password carefully
   - Check exact error message

4. If error says "Email not found":
   - Registration didn't work
   - Try registering again

5. If error says "Not verified":
   - Complete verification at `index.php?action=verify`

---

### Issue 7: 500 Internal Server Error

**Symptoms:** Blank page or error message when accessing site

**Causes:**
1. PHP syntax error in code
2. Database connection failed
3. Missing required files
4. Permission issues

**Debugging:**

1. **Check PHP Logs:**
   - Open: `http://localhost/php-email-auth/debug.php`
   - If page loads → PHP is working
   - If blank → Check Apache logs

2. **Check Database:**
   - Open: `http://localhost/phpmyadmin`
   - Connect to `email_auth_db`
   - If connects → Database working
   - If fails → Run `DATABASE_SETUP.sql`

3. **Check File Permissions:**
   - Go to: `http://localhost/php-email-auth/debug.php`
   - Check "logs" directory status
   - Should be writable (755 or 777)

4. **Check Required Files:**
   - File check section in `debug.php`
   - All required files should show ✓

5. **If still failing:**
   - Check Apache error log:
     ```
     C:\xampp\apache\logs\error.log
     ```
   - Check PHP error log in debug.php

---

## 🛠️ Administrative Tools

### Dashboard (`dashboard.php`)
**Purpose:** Central hub for all tools
**Use:** Navigate between different panels

Go to: `http://localhost/php-email-auth/dashboard.php`

### Debug Panel (`debug.php`)
**Purpose:** System health check
**Use When:** Something isn't working
**Shows:**
- PHP version
- Database status
- File structure
- Recent registrations
- Error logs

Go to: `http://localhost/php-email-auth/debug.php`

### Logs Viewer (`logs.php`)
**Purpose:** View all verification codes and emails
**Use When:** Need to get verification code
**Features:**
- Shows all registrations
- Displays verification codes
- One-click copy button
- Clear logs option

Go to: `http://localhost/php-email-auth/logs.php`

### Test Registration (`test-register.php`)
**Purpose:** Isolated registration testing
**Use When:** Testing email sending
**Benefits:**
- No session interference
- Direct feedback
- Shows errors clearly

Go to: `http://localhost/php-email-auth/test-register.php`

---

## 📋 Complete Troubleshooting Flowchart

```
START: System Not Working
  ↓
Is the page loading at all?
  ├─ NO → Check if Apache is running
  │      → Check if PHP is installed
  │      → Go to debug.php
  └─ YES ↓
  
Is database connected?
  ├─ NO → Run DATABASE_SETUP.sql
  │      → Check credentials in config/db.php
  └─ YES ↓

Can you register an account?
  ├─ NO → Check registration form - look for field issues
  │      → Check config/email.php syntax
  └─ YES ↓

Does email arrive in inbox?
  ├─ NO → Check SPAM/Promotions folder
  │      → Go to debug.php, check Gmail credentials
  │      → Check Test Registration page for errors
  │      → Look at logs.php for error messages
  └─ YES ↓

Can you click verification link?
  ├─ NO → Link might be broken
  │      → Copy link from logs.php
  │      → Paste in browser manually
  └─ YES ↓

Is account marked as verified?
  ├─ NO → Email link didn't work
  │      → Use manual code entry at verify.php
  │      → Code should be in logs.php
  └─ YES ↓

Can you login?
  ├─ NO → Check email/password
  │      → Check account exists in debug.php
  └─ YES ↓

✅ SYSTEM WORKING!
```

---

## 📞 Quick Reference

| Tool | URL | Purpose |
|------|-----|---------|
| Register | `/` | User registration |
| Verify | `/index.php?action=verify` | Email verification |
| Login | `/index.php?action=login` | User login |
| Home | `/index.php?action=home` | User dashboard |
| Dashboard | `/dashboard.php` | Central hub |
| Debug | `/debug.php` | System check |
| Logs | `/logs.php` | View emails/codes |
| Test | `/test-register.php` | Test registration |

---

## ✅ Everything Working Checklist

- [ ] Registration succeeds
- [ ] Email arrives in 5-10 seconds
- [ ] Email contains verification code
- [ ] Email contains verification link
- [ ] Clicking link verifies account
- [ ] Manual code entry works
- [ ] Account marked as verified in DB
- [ ] Can login after verification
- [ ] Homepage loads
- [ ] Can logout
- [ ] All error messages are helpful

---

**If issue persists:**
1. Check all three diagnostic tools (debug.php, logs.php, test-register.php)
2. Review GMAIL_SETUP.md for configuration
3. Check error logs with debug.php
4. Review COMPLETE_FLOW.md for expected behavior

**Still need help?** Try registering fresh account and complete flow step-by-step!

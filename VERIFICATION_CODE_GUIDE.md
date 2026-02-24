# Email Verification Code System - Complete Guide

## New Verification Flow

Your email authentication system now uses a **verification code** system instead of auto-verification links.

### 🎯 The New Flow

```
1. USER REGISTERS
   ↓
2. VERIFICATION CODE SENT
   └─ Shown in success message
   └─ Emailed to user
   └─ Logged in system
   ↓
3. USER ENTERS CODE
   └─ Go to Verification Page
   └─ Paste/Type verification code
   └─ Click "Verify Code"
   ↓
4. ACCOUNT VERIFIED
   └─ Redirected to login
   └─ Login with email & password
   ↓
5. USER IS LOGGED IN ✅
```

---

## Step-By-Step Instructions for Users

### Register an Account

1. Go to: **http://localhost/php-email-auth/**
2. Click **"Register here"**
3. Enter your email and password (minimum 6 characters)
4. Click **"Register"**

**Expected result:**
- ✅ Green success message
- 📧 Blue "Next Step" box appears
- 📋 "Go to Verification Page" button

### Verify Your Email

#### Option 1: Using Verification Code (Recommended for Development)

1. Click **"Go to Verification Page"** button OR go to: **http://localhost/php-email-auth/index.php?action=verify**
2. You'll see an input field for the verification code
3. Get your verification code from one of these options:

   **Option A: From Email Logs (Development)**
   - Click the "Go to Verification Page" link on registration page
   - Or visit: **http://localhost/php-email-auth/logs.php**
   - Find your email in the list
   - Copy the verification code (blue box)
   - Paste it in the verification form

   **Option B: From System Email (Production)**
   - Check your registered email inbox
   - Look for subject: "Email Verification - Email Authentication System"
   - Find the verification code in the email

4. Paste/type the code in the input field
5. Click **"Verify Code"**

**Expected result:**
- ✅ Green success message: "Email verified successfully!"
- 🔗 Link to login page

#### Option 2: Using Link from Email

1. In your email, you'll see a blue "Verify Account" button
2. Click it OR copy the verification link
3. The link contains the code and will auto-verify your account
4. You'll be redirected to login page after 3 seconds

### Login with Your Account

1. Go to: **http://localhost/php-email-auth/** (or click login link after verification)
2. Enter your **email** and **password**
3. Click **"Login"**
4. ✅ You're logged in! Welcome to your dashboard

---

## For Developers - Key Changes

### Modified Files

1. **models/user.php**
   - Email now highlights verification code
   - Shows both code and link options
   - Logs system shows code prominently

2. **views/register.php**
   - Shows direct link to verification page
   - Clear next steps after registration

3. **views/verify.php**
   - Input field for manual code entry
   - Handles both form submission and URL links
   - Shows success/error messages appropriately

4. **index.php**
   - Handles both POST (form submission) and GET (email link)
   - Auto-redirects after link verification
   - Full error handling

5. **logs.php** (COMPLETELY REDESIGNED)
   - Much prettier UI
   - Displays codes prominently
   - One-click copy button
   - Shows both code and link options

---

## Email Content Example

When a user registers, they receive an email like this:

```
═══════════════════════════════════════════════════════════════

Welcome to Email Authentication System!

Thank you for registering. Please verify your email using the code below:

┌─────────────────────────────────────────────────────────────┐
│                                                             │
│  Your Verification Code:                                    │
│  a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6                         │
│                                                             │
└─────────────────────────────────────────────────────────────┘

OPTION 1: Enter Code Manually
1. Go to: http://localhost/php-email-auth/index.php?action=verify
2. Enter the code above
3. Click Verify

OPTION 2: Click Verification Link
http://localhost/php-email-auth/index.php?action=verify&code=a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6

═══════════════════════════════════════════════════════════════
```

---

## Testing the System

### Quick Test Flow

```
1. Visit: http://localhost/php-email-auth/
2. Register with: test@example.com | password123
3. Click "Go to Verification Page" button
4. Check logs.php for your verification code
5. Copy the code and paste in the form
6. Click "Verify Code"
7. You'll be redirected to login
8. Login with your credentials
9. Welcome page appears ✅
```

### Troubleshooting

#### "Verification Code Not Found"
- Check the email logs at: http://localhost/php-email-auth/logs.php
- Make sure you're using the correct code
- Code is case-sensitive

#### "Invalid Verification Code"
- Double-check the code is copied exactly
- No extra spaces before/after
- Try clicking the link from the email instead

#### "Can't Find Verification Page"
- URL: http://localhost/php-email-auth/index.php?action=verify
- Or click the button on the registration success page

---

## Code Verification Details

### Verification Code Format
- **Length:** 32 characters (hexadecimal)
- **Example:** `a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6`
- **Case Sensitive:** Yes
- **Expires:** Never (or after 24 hours in production)

### Database Storage
The verification code is stored in the `users` table:
```sql
SELECT email, verification_code, is_verified FROM users
WHERE email = 'test@example.com';
```

After successful verification:
- `is_verified` is set to `1`
- `verification_code` is set to `NULL`
- User can now login

---

## Production Deployment Checklist

When moving to production:

- [ ] Configure real email service (Gmail, SendGrid, etc.)
- [ ] Update email sender address from `noreply@emailauth.com`
- [ ] Remove logs.php from production (security risk)
- [ ] Disable email logging (remove logEmailToFile calls)
- [ ] Set code expiration time (24 hours recommended)
- [ ] Implement rate limiting on verification attempts
- [ ] Use HTTPS for all traffic
- [ ] Add CSRF token to form
- [ ] Remove debug.php and test-register.php
- [ ] Update verification link domain to production URL
- [ ] Configure proper error handling (don't show raw errors)

---

## API Endpoints (For Integration)

### Register User
```
POST /index.php?action=register
Parameters: email, password
Response: Success/Error message
```

### Verify Email
```
POST /index.php?action=verify
Parameters: code (verification code)
Response: Verification success/error

OR

GET /index.php?action=verify&code=CODE
Response: Auto-verify with redirect
```

### Login User
```
POST /index.php?action=login
Parameters: email, password
Response: Redirect to home if successful
```

---

## Email Template Variables

In the email, these variables are available:
- `{code}` - The verification code
- `{email}` - User's email address
- `{link}` - Full verification link with code
- `{app_name}` - Application name
- `{app_url}` - Application URL

---

## Security Notes

1. **Verification codes are secure:**
   - Generated using `bin2hex(random_bytes(16))`
   - Stored as database unique constraint
   - Used only once

2. **Database Protection:**
   - Prepared statements prevent SQL injection
   - Passwords hashed with BCRYPT
   - Verification codes are unique

3. **Session Security:**
   - HTTPOnly cookies
   - Session timeout (1 hour recommended)
   - CSRF tokens (recommended for production)

---

## Common Questions

### Q: Can users verify without clicking email?
**A:** Yes! They can manually enter the code from their email or logs.php

### Q: What if user loses the email?
**A:** They can get the code from logs.php in development, or in production they can request a new code

### Q: How long are codes valid?
**A:** Indefinitely until used. You can add expiration in production.

### Q: Can codes be reused?
**A:** No, after verification the code is deleted from database

### Q: What happens if wrong code entered?
**A:** User sees error and can try again with correct code

---

## Summary of Changes

| Aspect | Before | After |
|--------|--------|-------|
| **Verification Method** | Click email link | Enter code manually |
| **Email Content** | Just a link | Code + Link options |
| **User Flow** | Register → Click link → Auto-verified | Register → Enter code → Verify |
| **For developers** | Logs showed links | Logs show codes prominently |
| **Logs UI** | Dark/plain | Modern/colorful with copy button |
| **User Control** | Automatic | Manual (more secure) |

---

## Support

For issues with verification:
1. Check: http://localhost/php-email-auth/debug.php
2. Check logs: http://localhost/php-email-auth/logs.php
3. Read: TROUBLESHOOTING.md
4. Test: http://localhost/php-email-auth/test-register.php

Happy verifying! 🚀

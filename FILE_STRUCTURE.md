# 📁 File Structure & Purpose Reference

## System Architecture

```
php-email-auth/
│
├── 📄 index.php                          ← MAIN ROUTER (entry point)
├── 📄 config/
│   ├── db.php                           ← Database connection
│   └── email.php                        ← Gmail configuration ⭐ UPDATE THIS
│
├── 📄 controllers/
│   └── AuthController.php               ← Request handler
│
├── 📄 models/
│   └── User.php                         ← Database operations & email logic
│
├── 📄 views/
│   ├── register.php                     ← Registration form
│   ├── login.php                        ← Login form
│   ├── verify.php                       ← Email verification page
│   └── home.php                         ← User dashboard
│
├── 📄 public/
│   └── style.css                        ← All styling
│
├── 📄 logs/
│   └── emails.log                       ← Email backup log
│
├── 📄 DATABASE_SETUP.sql                ← Database creation script
│
├── 🔧 Development Tools
│   ├── dashboard.php                    ← Central dashboard
│   ├── debug.php                        ← System diagnostics
│   ├── logs.php                         ← View verification codes
│   └── test-register.php                ← Test registration
│
└── 📚 Documentation
    ├── SETUP.md                         ← Start here! ⭐
    ├── GMAIL_SETUP.md                   ← Detailed Gmail config
    ├── COMPLETE_FLOW.md                 ← User workflow
    ├── TROUBLESHOOTING_GUIDE.md         ← Debugging help
    ├── README.md                        ← Full documentation
    └── FILE_STRUCTURE.md                ← This file
```

---

## 🎯 Key Files Explained

### ⭐ **config/email.php** (MOST IMPORTANT)
**Status:** You MUST edit this
**What it does:** Stores Gmail credentials
**What to change:**
```php
define('MAIL_USERNAME', 'your-email@gmail.com');  // ← Your Gmail
define('MAIL_PASSWORD', 'your-app-password');      // ← 16-char App Password
define('MAIL_FROM', 'your-email@gmail.com');       // ← Your Gmail
```

---

### 📄 **index.php** (Main Router)
**Status:** Pre-configured, no edits needed
**What it does:** Routes all requests (register/login/verify)
**How it works:**
- Receives ?action=register|login|verify|home|logout
- Calls appropriate controller method
- Includes correct view

---

### 📄 **config/db.php** (Database Connection)
**Status:** Pre-configured
**What it does:** 
- Connects to MySQL
- Creates session
- Starts session

---

### 📄 **controllers/AuthController.php** (Request Handler)
**Status:** Pre-configured
**What it does:**
- Gets data from forms
- Calls User model methods
- Manages session for login

---

### 📄 **models/User.php** (Core Logic)
**Status:** Pre-configured
**What it does:**
- Registers users
- Sends verification emails (via Gmail)
- Verifies accounts
- Authenticates logins
- **Handles both:**
  - Sending emails via PHPMailer
  - Sending emails via PHP mail()
  - Logging emails to file

**Key Methods:**
- `register($email, $password)` - Create new account
- `sendVerificationEmail($email, $code)` - Send via Gmail
- `sendEmailViaPhpMailer()` - Uses PHPMailer library
- `sendEmailViaPhpMail()` - Uses PHP mail()
- `verify($code)` - Mark email as verified
- `login($email, $password)` - Authenticate user
- `logEmailToFile()` - Backup log for testing

---

### 🎨 **views/register.php** (Registration Page)
**Status:** Pre-configured
**Shows:**
- Registration form (initial)
- Success message with Gmail inbox instructions
- Error message with retry form

---

### 🎨 **views/login.php** (Login Page)
**Status:** Pre-configured
**Shows:**
- Login form with email/password
- Error message if login fails
- Link to registration if no account

---

### 🎨 **views/verify.php** (Verification Page)
**Status:** Pre-configured
**Shows:**
- Verification code input form
- Success message after verification
- Option to use email link instead
- Next steps instructions

---

### 🎨 **views/home.php** (User Dashboard)
**Status:** Pre-configured
**Shows:**
- User email
- Welcome message
- Logout button
- Account status

---

### 🎨 **public/style.css** (Styling)
**Status:** Pre-configured
**Contains:**
- Layout & positioning
- Colors & gradients
- Form styling
- Message styling
- Responsive design

---

## 🔧 Development Tools

### **dashboard.php** (Central Hub)
**Purpose:** Main navigation point
**Access:** `http://localhost/php-email-auth/dashboard.php`
**Shows:**
- Quick stats
- Links to all tools
- System status

---

### **debug.php** (System Diagnostics)
**Purpose:** Check system health
**Access:** `http://localhost/php-email-auth/debug.php`
**Checks:**
- ✓ PHP version
- ✓ Database connection
- ✓ Users table exists
- ✓ Files structure
- ✓ Logs directory writable
- ✓ Recent registrations

**When to use:** Something's broken

---

### **logs.php** (Email Log Viewer)
**Purpose:** View verification codes and sent emails
**Access:** `http://localhost/php-email-auth/logs.php`
**Shows:**
- All registrations
- Verification codes
- Verification links
- One-click copy button
- Clear logs option

**When to use:** Need to get verification code for testing

---

### **test-register.php** (Isolated Testing)
**Purpose:** Test registration without session interference
**Access:** `http://localhost/php-email-auth/test-register.php`
**Use for:**
- Testing registration process
- Testing email sending
- Debugging without sessions
- Seeing specific error messages

---

## 📚 Documentation Files

### **SETUP.md** (Start Here!)
Read this first for quick setup
- Gmail configuration steps
- Quick checklist
- Common issues

### **GMAIL_SETUP.md** (Detailed Gmail Guide)
Complete guide for Gmail integration
- Step-by-step 2FA setup
- App Password generation
- Troubleshooting SMTP errors
- Environment variables

### **COMPLETE_FLOW.md** (User Workflow)
Explains complete user journey
- Registration → Email → Verification → Login
- Step-by-step with screenshots
- What should happen at each stage
- Success criteria

### **TROUBLESHOOTING_GUIDE.md** (Debug Help)
Comprehensive debugging guide
- Common issues and solutions
- Error messages explained
- Debugging flowchart
- All tools reference

### **README.md** (Full Documentation)
Complete system documentation
- Requirements
- Installation
- Features
- Architecture overview

---

## 🔄 User Journey Through Files

### 1. **Registration Flow**
```
User visits index.php
  ↓
Form shown from views/register.php
  ↓
Form submits to index.php
  ↓
AuthController.register() called
  ↓
User.register() validates & inserts
  ↓
User.sendVerificationEmail() sends email
  ↓
Success message shown (register.php)
```

### 2. **Email Sending Process**
```
User.sendVerificationEmail() called
  ↓
Creates HTML email content
  ↓
USER.sendEmailViaGmail() 
  ├─ Try PHPMailer method
  └─ Fallback to PHP mail()
  ↓
Connects to smtp.gmail.com:587
  ↓
Authenticates with email.php credentials
  ↓
Sends HTML email ✅
  ↓
User.logEmailToFile() makes backup
  ↓
Email stored in logs/emails.log
```

### 3. **Verification Flow**
```
User clicks link or enters code
  ↓
Request to index.php?action=verify
  ↓
AuthController.verify() processes
  ↓
User.verify() looks up code
  ↓
Marks account as verified (is_verified = 1)
  ↓
Success message shown (verify.php)
  ↓
User redirected to login
```

### 4. **Login Flow**
```
User visits index.php?action=login
  ↓
Form shown from views/login.php
  ↓
Form submits with email/password
  ↓
AuthController.login() called
  ↓
User.login() verifies credentials
  ├─ Checks email exists
  ├─ Checks password matches
  └─ Checks is_verified = 1
  ↓
Session created with user data
  ↓
User redirected to home.php
```

---

## 🔐 Data Flow

### Database Flow
```
Registration:
  User input → Validation → BCRYPT hashing → Insert to DB ✅

Verification:
  Email link/code → Lookup in DB → Update is_verified ✅

Login:
  Email/password → Lookup in DB → Compare password → Create session ✅
```

### Email Flow
```
Registration → Generate code → Create HTML → Send via Gmail ✅

Or (if Gmail fails):
  → Log to file instead (development fallback)
```

---

## 📊 Environment Variables (Optional for Production)

Instead of editing `config/email.php` directly:

```php
define('MAIL_USERNAME', getenv('MAIL_USERNAME') ?: 'default@gmail.com');
define('MAIL_PASSWORD', getenv('MAIL_PASSWORD') ?: 'default-app-password');
define('MAIL_FROM', getenv('MAIL_FROM') ?: 'default@gmail.com');
```

Then set via:
- `.env` file (with dotenv library)
- Server environment
- Docker environment variables

---

## ✅ File Checklist

Essential files (all must exist):
- [ ] `index.php` - Main router
- [ ] `config/db.php` - Database connection
- [ ] `config/email.php` - Gmail config (EDIT THIS!)
- [ ] `controllers/AuthController.php` - Request handler
- [ ] `models/User.php` - Business logic
- [ ] `views/register.php` - Registration page
- [ ] `views/login.php` - Login page
- [ ] `views/verify.php` - Verification page
- [ ] `views/home.php` - User dashboard
- [ ] `public/style.css` - Styling
- [ ] `DATABASE_SETUP.sql` - Database schema

Optional development files:
- [ ] `dashboard.php` - Central hub
- [ ] `debug.php` - System diagnostics
- [ ] `logs.php` - Log viewer
- [ ] `test-register.php` - Testing interface

Documentation files:
- [ ] `SETUP.md` - Quick setup
- [ ] `GMAIL_SETUP.md` - Gmail guide
- [ ] `COMPLETE_FLOW.md` - Workflow guide
- [ ] `TROUBLESHOOTING_GUIDE.md` - Debug help
- [ ] `README.md` - Full docs

---

## 🎯 Starting Points for Different Tasks

### I want to register a user
→ Go to: `http://localhost/php-email-auth/`

### I want to verify my email
→ Go to: `http://localhost/php-email-auth/index.php?action=verify`

### I want to login
→ Go to: `http://localhost/php-email-auth/index.php?action=login`

### I want to test registration without side effects
→ Go to: `http://localhost/php-email-auth/test-register.php`

### I want to see verification codes
→ Go to: `http://localhost/php-email-auth/logs.php`

### Something is broken
→ Go to: `http://localhost/php-email-auth/debug.php`

### I want to configure Gmail
→ Edit: `config/email.php`
→ Read: `GMAIL_SETUP.md`

### I want to understand the complete flow
→ Read: `COMPLETE_FLOW.md`

### I need help fixing an error
→ Read: `TROUBLESHOOTING_GUIDE.md`

---

**All set! Start with SETUP.md for quick configuration.**
